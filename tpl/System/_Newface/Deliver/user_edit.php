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
</style>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">

                        <form id="myform" method="post" action="{pigcms{:U('Deliver/user_edit')}" frame="true" refresh="true">

                            <input type="hidden" name="uid" value="{pigcms{$now_user.uid}"/>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_LAST_NAME_')}</label>
                                <div class="col-sm-9"><input type="text" size="50" class="form-control"
                                                             name="family_name"
                                                             value="{pigcms{$now_user.family_name}" size="50" validate="maxlength:50,required:true"/>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_FIRST_NAME_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="name"
                                                             value="{pigcms{$now_user.name}" size="20" validate="maxlength:50,required:true"/>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_EMAIL_TXT_')}</label>
                                <div class="col-sm-9"><input type="text" size="50" class="form-control"
                                                             name="email"
                                                             value="{pigcms{$now_user.email}" size="20" validate="maxlength:50,required:true"/>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_LANG_TXT_')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable"><label class="cb-enable <if condition="$now_user['language'] eq 1">selected</if>"><span>English</span><input type="radio" name="language" value="1" <if condition="$now_user['language'] eq 1">checked="checked"</if> /></label></span>
                                    <span class="cb-disable"><label class="cb-disable <if condition="$now_user['language'] eq 0">selected</if>"><span>Chinese</span><input type="radio" name="language" value="0" <if condition="$now_user['language'] eq 0">checked="checked"</if> /></label></span>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_B_D_LOGIN_KEY1_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="pwd"
                                                             value="" tips="{pigcms{:L('K_DNFIINM')}"  size="20" validate="required:false"/></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STATUS_')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable"><label class="cb-enable <if condition="
                                                                   $now_user['status'] eq 1">selected</if>
                                        "><span>{pigcms{:L('_BACK_NORMAL_')}</span><input type="radio" name="status"
                                                                                          value="1"  <if
                                                condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
                                    <span class="cb-disable"><label class="cb-disable <if condition=" $now_user['status'] eq 0">selected</if>
                                        "><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status"
                                                                                          value="0"  <if
                                                condition="$now_user['status'] eq 0">checked="checked"</if>/></label></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Work Status</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable"><label class="cb-enable <if condition="$now_user['work_status'] eq 0">selected</if>">
                                        <span>On-Shift</span>
                                        <input type="radio" name="work_status" value="0"  <if condition="$now_user['work_status'] eq 1">checked="checked"</if>/></label>
                                    </span>
                                    <span class="cb-disable"><label class="cb-disable <if condition=" $now_user['work_status'] eq 1">selected</if>">
                                        <span>Off-Shift</span>
                                        <input type="radio" name="work_status" value="1"  <if condition="$now_user['work_status'] eq 1">checked="checked"</if>/></label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_DELIVER_AREA_')}</label>
                                <div class="col-sm-9" id="city_area">{pigcms{$now_user.city_name}</div>
                                <input type="hidden" id="city_id" name="city_id" value="{pigcms{$now_user.city_id}">
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BIRTHDAY_TXT_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="birthday" value="{pigcms{$now_user.birthday}"
                                                             size="20" validate="maxlength:20,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_DELIVERY_AREA_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="range"
                                                             value="{pigcms{$now_user.range}"  validate="maxlength:20,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_PHONE_NUM_')}</label>
                                <div class="col-sm-9"><input type="text"  class="form-control"
                                                             name="phone"
                                                             value="{pigcms{$now_user.phone}" size="20"
                                                             validate="number:true,required:true"/></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_OFEN_ADD_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="adress" id="adress" readonly="readonly"
                                                             value="{pigcms{$now_user.site}"  validate="required:true"/></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_COURIER_LOC_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="long_lat" id="long_lat"  readonly="readonly"
                                                             value="{pigcms{$now_user.lng},{pigcms{$now_user.lat}" validate="required:true"/></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">SIN Number</label>
                                <div class="col-sm-9"><input type="text" size="30" class="form-control"
                                                             name="sin_num" placeholder="SIN Number"
                                                             value="{pigcms{$img.sin_num}"  validate="required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('D_COURIER_NOTES')}</label>
                                <div class="col-sm-9"><textarea name="remark" class="form-control">{pigcms{$now_user.remark}</textarea>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_BANK_INFO_')}</label>
                                <div class="col-sm-9"><input type="text" size="30" class="form-control"
                                                             name="ahname" placeholder="Account Holder Name"
                                                             value="{pigcms{$card.ahname}"
                                                             validate="maxlength:50,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9"><input type="text" size="30" class="form-control"
                                                             name="transit" placeholder="Transit(Branch)"
                                                             value="{pigcms{$card.transit}"
                                                             validate="maxlength:50,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9"><input type="text" size="30" class="form-control"
                                                             name="institution" placeholder="Institution"
                                                             value="{pigcms{$card.institution}"
                                                             validate="maxlength:50,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9"><input type="text" size="30" class="form-control"
                                                             name="account" placeholder="Account"
                                                             value="{pigcms{$card.account}"
                                                             validate="maxlength:50,required:true"/>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_DRIVER_LIC_')}</label>
                                <div class="col-sm-9" id="J_selectImage_0" style="display:inline-block;" >
                                    <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                                        {pigcms{:L('_ND_UPLOAD1_')}
                                    </div>
                                </div>
                                <if condition="$img['driver_license'] eq ''">
                                    <div class="img_0">
                                    </div>
                                    <else/>
                                    <div class="img_0" style="height: 100px">
                                        <img src="{pigcms{:C('config.site_url')}{pigcms{$img['driver_license']}"
                                             height="100"/>
                                    </div>
                                </if>
                            </div>
                            <!--车辆保险-->
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_VEHICLE_INSU_')}</label>
                                <div class="col-sm-9" id="J_selectImage_1" style="display:inline-block;" >
                                    <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                                        {pigcms{:L('_ND_UPLOAD2_')}
                                    </div>
                                </div>
                                <if condition="$img['insurance'] eq ''">
                                    <div class="img_1">

                                    </div>
                                    <else/>
                                    <div class="img_1" style="height: 100px">
                                        <img src="{pigcms{:C('config.site_url')}{pigcms{$img['insurance']}"
                                             height="100"/>
                                    </div>
                                </if>
                            </div>
                            <!--工作证明-->
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_PROOF_WORK_')}</label>
                                <div class="col-sm-9" id="J_selectImage_2" style="display:inline-block;" >
                                    <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                                        {pigcms{:L('_ND_UPLOAD3_')}
                                    </div>
                                </div>
                                <if condition="$img['certificate'] eq ''">
                                    <div class="img_2">

                                    </div>
                                    <else/>
                                    <div class="img_2" style="height: 100px">
                                        <img src="{pigcms{:C('config.site_url')}{pigcms{$img['certificate']}"
                                             height="100"/>
                                    </div>
                                </if>
                            </div>
                            <input type="hidden" name="driver_license" id="filename_0"
                                   value="{pigcms{$img['driver_license']}">
                            <input type="hidden" name="insurance" id="filename_1"
                                   value="{pigcms{$img['insurance']}">
                            <input type="hidden" name="certificate" id="filename_2"
                                   value="{pigcms{$img['certificate']}">
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}" class="button tutti_hidden_obj" />
                                <input type="reset" value="{pigcms{:L('_BACK_CANCEL_')}" class="button tutti_hidden_obj" />
                            </div>
                        </form>
                        <div id="modal-table" class="map-modal" tabindex="-1" style="display:block;">
                            <div class="map-modal-dialog" style="width:98%;">
                                <div class="modal-content" style="width:100%;">
                                    <div class="modal-header no-padding" style="width:100%;">
                                        <div class="table-header">
                                               {pigcms{:L('_BACK_DRAG_RED_PIN_')}
                                        </div>
                                    </div>
                                    <div class="modal-body no-padding" style="width:100%;">
                                        <form id="map-search" style="margin:10px;">
                                            <input id="map-keyword" type="textbox" style="width:300px;" placeholder="尽量填写城市、区域、街道名" value="{pigcms{$now_user.site}"/>
                                            <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}"/>
                                        </form>
                                        <div style="width: 100%; height: 250px; min-height: 250px;" id="cmmap"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
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
    var lang="en";

    $('input[name="birthday"]').mobiscroll().date({
        theme: theme,
        mode: mode,
        display: display,
        dateFormat: 'yyyy-mm-dd',
        dateOrder:'yymmdd',
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
	<!--<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script>
<include file="Public:footer_inc"/>