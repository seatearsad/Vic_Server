<include file="Public:header"/>
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


                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}"
                                       class="button tutti_hidden_obj"/>
                                <input type="reset" value="{pigcms{:L('_BACK_CANCEL_')}"
                                       class="button tutti_hidden_obj"/>
                            </div>

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
<script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
	<script type="text/javascript">
	var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",merchant_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}",choose_city_name="{pigcms{:U('Area/ajax_city_name')}";

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
	</script>
	<!--<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script>
<include file="Public:footer_inc"/>