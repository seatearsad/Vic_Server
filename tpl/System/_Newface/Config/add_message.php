<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Config/modify_message')}" enctype="multipart/form-data">
                            <input name="id" value="{pigcms{$message.id|default='0'}" type="hidden">
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_NAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" size="20" validate="maxlength:200,required:true" value="{pigcms{$message.name|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_TYPE')}</label>
                                <div class="col-sm-9">
                                    <select name="type" id="select_type" class="form-control">
                                        <option value="0" <if condition="$message.type eq 0">selected</if>>Text</option>
                                        <option value="1" <if condition="$message.type eq 1">selected</if>>Image</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="image_div">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('UPLOAD_BKADMIN')}</label>
                                <div class="col-sm-9" id="J_selectImage_0" style="display:inline-block;" >
                                    <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                                        {pigcms{:L('UPLOAD_BKADMIN')}
                                    </div>
                                    <if condition="$message['content'] eq ''">
                                        <div class="img_0">
                                        </div>
                                        <else/>
                                        <div class="img_0" style="height: 100px">
                                            <img src="{pigcms{:C('config.site_url')}{pigcms{$message['content']}" height="100"/>
                                        </div>
                                    </if>
                                    <input type="hidden" name="content_img" id="filename_0" value="{pigcms{$message['content']}">
                                </div>
                            </div>
                            <div class="form-group row" id="text_div">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_CONTENT')}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="content" validate="">{pigcms{$message.content|default=''}</textarea>
                                </div>
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    Set the button name by changing the "Name" above
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_URL')} (update_app)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="link" size="20" validate="maxlength:200" value="{pigcms{$message.link|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row" id="city_tr">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_CITY')}</label>
                                <div class="col-sm-9">
                                    <select name="city_id" id="city_select" class="form-control">
                                        <option value="0" data-type="0" <if condition="!$message or $message['city_id'] eq 0">selected="selected"</if>>{pigcms{:L('G_UNIVERSAL')}</option>
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}" data-type="{pigcms{$vo.range_type}" <if condition="$message and $message['city_id'] eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_START_TIME')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" name="begin_time" style="width:120px;" id="d4311" validate="required:true" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'d4312\')}'})" <if condition="$message['begin_time'] neq 0">value="{pigcms{$message.begin_time|date='Y-m-d',###}"</if>/>
                                    <span id="clear_begin" class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">{pigcms{:L('G_CLEAR')}</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_END_TIME')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" name="end_time" style="width:120px;" id="d4312" validate="required:true" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'#F{$dp.$D(\'d4311\')}'})" <if condition="$message['end_time'] neq 0">value="{pigcms{$message.end_time|date='Y-m-d',###}"</if>/>
                                    <span id="clear_end" class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">{pigcms{:L('G_CLEAR')}</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">App Version</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="version" size="20" validate="maxlength:200,required:true" value="{pigcms{$message.version|default='0'}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('QW_LISTINGORDER')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sort" size="20" validate="maxlength:200,required:true" value="{pigcms{$message.sort|default='0'}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Wap</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable">
                                        <label class="cb-enable <if condition=" $message['is_wap'] eq 1 or $message['is_wap'] eq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_NORMAL_')}</span>
                                        <input type="radio" name="is_wap" value="1"  <if condition="$message['is_wap'] eq 1 or $message['is_wap'] eq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                    <span class="cb-disable">
                                        <label class="cb-disable <if condition=" $message['is_wap'] eq 0 and $message['is_wap'] neq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_FORBID_')}</span>
                                        <input type="radio" name="is_wap" value="0"  <if condition="$message['is_wap'] eq 0 and $message['is_wap'] neq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">iOS</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable">
                                        <label class="cb-enable <if condition=" $message['is_ios'] eq 1 or $message['is_ios'] eq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_NORMAL_')}</span>
                                        <input type="radio" name="is_ios" value="1"  <if condition="$message['is_ios'] eq 1 or $message['is_ios'] eq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                    <span class="cb-disable">
                                        <label class="cb-disable <if condition=" $message['is_ios'] eq 0 and $message['is_ios'] neq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_FORBID_')}</span>
                                        <input type="radio" name="is_ios" value="0"  <if condition="$message['is_ios'] eq 0 and $message['is_ios'] neq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Android</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable">
                                        <label class="cb-enable <if condition=" $message['is_android'] eq 1 or $message['is_android'] eq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_NORMAL_')}</span>
                                        <input type="radio" name="is_android" value="1"  <if condition="$message['is_android'] eq 1 or $message['is_android'] eq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                    <span class="cb-disable">
                                        <label class="cb-disable <if condition=" $message['is_android'] eq 0 and $message['is_android'] neq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_FORBID_')}</span>
                                        <input type="radio" name="is_android" value="0"  <if condition="$message['is_android'] eq 0 and $message['is_android'] neq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STATUS_')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable">
                                        <label class="cb-enable <if condition=" $message['status'] eq 1 or $message['is_android'] eq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_NORMAL_')}</span>
                                        <input type="radio" name="status" value="1"  <if condition="$message['status'] eq 1 or $message['is_android'] eq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                    <span class="cb-disable">
                                        <label class="cb-disable <if condition=" $message['status'] eq 0 and $message['is_android'] neq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_FORBID_')}</span>
                                        <input type="radio" name="status" value="0"  <if condition="$message['status'] eq 0 and $message['is_android'] neq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Close Button</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable">
                                        <label class="cb-enable <if condition=" $message['is_close'] eq 1 or $message['is_close'] eq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_NORMAL_')}</span>
                                        <input type="radio" name="is_close" value="1"  <if condition="$message['is_close'] eq 1 or $message['is_close'] eq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                    <span class="cb-disable">
                                        <label class="cb-disable <if condition=" $message['is_close'] eq 0 and $message['is_close'] neq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_FORBID_')}</span>
                                        <input type="radio" name="is_close" value="0"  <if condition="$message['is_close'] eq 0 and $message['is_close'] neq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                </div>
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    When switch to "Inactive" customers will NOT be able to close the popup window. Please only switch off for mandatory app update.
                                </div>
                            </div>
                            <div class="form-group  row" id="select_area" style="display: none;">
                                <label class="col-sm-3 col-form-label">Within Defined Service Area</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable">
                                        <label class="cb-enable <if condition=" $message['in_area'] eq 1 or $message['in_area'] eq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_NORMAL_')}</span>
                                        <input type="radio" name="in_area" value="1"  <if condition="$message['in_area'] eq 1 or $message['in_area'] eq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                    <span class="cb-disable">
                                        <label class="cb-disable <if condition=" $message['in_area'] eq 0 and $message['in_area'] neq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_FORBID_')}</span>
                                        <input type="radio" name="in_area" value="0"  <if condition="$message['in_area'] eq 0 and $message['in_area'] neq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                </div>
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    This will only work for cities that have a defined area. "Active" means only apply to customers within the defined service area; "Inactive" means only for customers outside of the area
                                </div>
                            </div>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
                                <input type="reset" value="取消" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<style>
    #clear_begin,#clear_end{
        line-height: 30px;
        cursor: pointer;
        color: #0e62cd;
        padding-left: 10px;
    }

    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }

    .img_0{
        width: 100%;
        margin-top: 10px;
    }
    .img_0 img {
        height: 100px;
    }
</style>

<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
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

    $(function(){
        var sel_type=$("#select_type").val();
        if(sel_type == 0){
            $('#text_div').show();
            $('#image_div').hide();
        }else{
            $('#text_div').hide();
            $('#image_div').show();

            uploader.addButton({
                id: '#J_selectImage_0',
                name: 'image_0',
                multiple: false
            });
        }

        $('#clear_begin').click(function () {
            $('input[name=begin_time]').val('');
        });
        $('#clear_end').click(function () {
            $('input[name=end_time]').val('');
        });
        $('#select_type').change(function () {
            var type = $(this).val();
            if(type == 0){
                $('#text_div').show();
                $('#image_div').hide();
            }else{
                $('#text_div').hide();
                $('#image_div').show();

                uploader.addButton({
                    id: '#J_selectImage_0',
                    name: 'image_0',
                    multiple: false
                });
            }
        });

        $("#city_select").change(getCityType);

        getCityType();
    });

    function getCityType () {
        var city_id = $("#city_select").val();
        var city_type = 0;
        $("#city_select").find("option").each(function () {
            //console.log("city_select:" + $(this).data('type'));
            if(city_id == $(this).val()){
                city_type = $(this).data('type');
            }
        });

        if(city_type == 2){
            $("#select_area").show();
        }else{
            $("#select_area").hide();
        }
    }
</script>
<include file="Public:footer_inc"/>