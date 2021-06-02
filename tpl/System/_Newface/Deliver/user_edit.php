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
                            <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
                                <tr>
                                    <th width="15%">{pigcms{:L('_LAST_NAME_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="family_name" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.family_name}"/></td>
                                    <th width="15%">{pigcms{:L('_FIRST_NAME_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="name" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.name}"/></td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_EMAIL_TXT_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="email" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.email}" /></td>
                                    <th width="15%">{pigcms{:L('_LANG_TXT_')}</th>
                                    <td width="35%">
                                        <span class="cb-enable"><label class="cb-enable <if condition="$now_user['language'] eq 1">selected</if>"><span>English</span><input type="radio" name="language" value="1" <if condition="$now_user['language'] eq 1">checked="checked"</if> /></label></span>
                                        <span class="cb-disable"><label class="cb-disable <if condition="$now_user['language'] eq 0">selected</if>"><span>Chinese</span><input type="radio" name="language" value="0" <if condition="$now_user['language'] eq 0">checked="checked"</if> /></label></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_B_D_LOGIN_KEY1_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="pwd" size="20" value="" tips="{pigcms{:L('K_DNFIINM')}" /></td>
                                    <th width="15%">{pigcms{:L('_BACK_STATUS_')}</th>
                                    <td width="35%" class="radio_box">
                                        <span class="cb-enable"><label class="cb-enable <if condition="$now_user['status'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_NORMAL_')}</span><input type="radio" name="status" value="1"  <if condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
                                        <span class="cb-disable"><label class="cb-disable <if condition="$now_user['status'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0"  <if condition="$now_user['status'] eq 0">checked="checked"</if>/></label></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_DELIVER_AREA_')}</th>
                                    <!--td id="choose_cityarea" colspan=3  province_id="{pigcms{$now_user.province_id}" city_id="{pigcms{$now_user.city_id}" area_id="{pigcms{$now_user.area_id}" circle_id="{pigcms{$now_user.circle_id}"></td-->
                                    <td id="city_area">
                                        {pigcms{$now_user.city_name}
                                    </td>
                                    <input type="hidden" id="city_id" name="city_id" value="{pigcms{$now_user.city_id}">
                                    <th width="15%">{pigcms{:L('_BIRTHDAY_TXT_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="birthday" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.birthday}"/></td>
                                <tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_DELIVERY_AREA_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="range" size="20" validate="required:true" value="{pigcms{$now_user.range}"/></td>
                                    <th width="15%">{pigcms{:L('_BACK_PHONE_NUM_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="phone" size="20" validate="number:true,required:true" value="{pigcms{$now_user.phone}"/></td>
                                <tr>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_OFEN_ADD_')}</th>
                                    <td width="35%"><input type="text" class="input fl" readonly="readonly" name="adress" id="adress" validate="required:true" value="{pigcms{$now_user.site}"/></td>
                                    <th width="15%">{pigcms{:L('_BACK_COURIER_LOC_')}</th>
                                    <td width="35%" class="radio_box"><input class="input fl" size="20" name="long_lat" id="long_lat" type="text" readonly="readonly" validate="required:true" value="{pigcms{$now_user.lng},{pigcms{$now_user.lat}"/></td>
                                </tr>
                                <tr>
                                    <th width="15%">SIN Number</th>
                                    <td colspan=3>
                                        <input type="text" placeholder="SIN Number" class="input fl" name="sin_num" size="30" validate="maxlength:50,required:true" value="{pigcms{$img.sin_num}" />
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('D_COURIER_NOTES')}</th>
                                    <td colspan=3>
                                        <textarea name="remark">{pigcms{$now_user.remark}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_BANK_INFO_')}</th>
                                    <td colspan=3>
                                        <input type="text" placeholder="Account Holder Name" class="input fl" name="ahname" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.ahname}" />
                                    </td>
                                <tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td colspan=3>
                                        <input type="text" placeholder="Transit(Branch)" class="input fl" name="transit" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.transit}" />
                                    </td>
                                <tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td colspan=3>
                                        <input type="text" placeholder="Institution" class="input fl" name="institution" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.institution}" />
                                    </td>
                                <tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td colspan=3>
                                        <input type="text" placeholder="Account" class="input fl" name="account" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.account}" />
                                    </td>
                                <tr>
                                <if condition="$img['driver_license']">
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_DRIVER_LIC_')}</th>
                                    <td colspan=3>
                                        <img src="{pigcms{:C('config.site_url')}{pigcms{$img['driver_license']}" height="100"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_VEHICLE_INSU_')}</th>
                                    <td colspan=3>
                                        <img src="{pigcms{:C('config.site_url')}{pigcms{$img['insurance']}" height="100"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_PROOF_WORK_')}</th>
                                    <td colspan=3>
                                        <img src="{pigcms{:C('config.site_url')}{pigcms{$img['certificate']}" height="100"/>
                                        <!--input type="text" placeholder="SIN_Number" class="input fl" name="certificate" size="30" validate="maxlength:50,required:true" value="{pigcms{$img.certificate}" /-->
                                    </td>
                                </tr>
                                </if>
                            </table>
                            <div class="btn hidden">
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