<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <form id="myform" method="post" action="{pigcms{:U('Deliver/user_add')}" frame="true" refresh="true">
                            <input type="hidden" name="uid" value="{pigcms{$now_user.uid}"/>
                            <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
                                <tr>
                                    <th width="15%">{pigcms{:L('_LAST_NAME_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="family_name" size="20" validate="maxlength:50,required:true"/></td>
                                    <th width="15%">{pigcms{:L('_FIRST_NAME_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="name" size="20" validate="maxlength:50,required:true"/></td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_EMAIL_TXT_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="email" size="20" validate="maxlength:50,required:true" /></td>
                                    <th width="15%">{pigcms{:L('_LANG_TXT_')}</th>
                                    <td width="35%">
                                        <span class="cb-enable"><label class="cb-enable"><span>English</span><input type="radio" name="language" value="1"  /></label></span>
                                        <span class="cb-disable"><label class="cb-disable selected"><span>Chinese</span><input type="radio" name="language" value="0"  /></label></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_B_D_LOGIN_KEY1_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="pwd" size="20" value="123456" validate="required:true"/></td>
                                    <th width="15%">{pigcms{:L('_BACK_STATUS_')}</th>
                                    <td width="35%" class="radio_box">
                                        <span class="cb-enable"><label class="cb-enable <if condition="$now_user['status'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_NORMAL_')}</span><input type="radio" name="status" value="1"  <if condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
                                        <span class="cb-disable"><label class="cb-disable <if condition="$now_user['status'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0"  <if condition="$now_user['status'] eq 0">checked="checked"</if>/></label></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_DELIVER_AREA_')}</th>
                                    <!--td id="choose_cityarea" colspan=3></td-->
                                    <td id="city_area">

                                    </td>
                                    <input type="hidden" id="city_id" name="city_id">
                                    <th width="15%">{pigcms{:L('_BIRTHDAY_TXT_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="birthday" size="20" validate="maxlength:50,required:true" /></td>
                                <tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_DELIVERY_AREA_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="range" size="20" validate="required:true" value="5"/></td>
                                    <th width="15%">{pigcms{:L('_BACK_PHONE_NUM_')}</th>
                                    <td width="35%"><input type="text" class="input fl" name="phone" size="20" validate="number:true,required:true" value="{pigcms{$now_user.phone}"/></td>
                                <tr>
                                </tr>
                                <tr>
                                    <th width="15%">{pigcms{:L('_BACK_OFEN_ADD_')}</th>
                                    <td width="35%"><input type="text" class="input fl" readonly="readonly" name="adress" id="adress" validate="required:true"/></td>
                                    <th width="15%">{pigcms{:L('_BACK_COURIER_LOC_')}</th>
                                    <td width="35%" class="radio_box"><input class="input fl" size="20" name="long_lat" id="long_lat" type="text" readonly="readonly" validate="required:true"/></td>
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
                                            <input type="text" placeholder="Account Holder Name" class="input fl" name="ahname" size="30" validate="maxlength:50,required:true" />
                                    </td>
                                <tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td colspan=3>
                                        <input type="text" placeholder="Transit(Branch)" class="input fl" name="transit" size="30" validate="maxlength:50,required:true" />
                                    </td>
                                <tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td colspan=3>
                                        <input type="text" placeholder="Institution" class="input fl" name="institution" size="30" validate="maxlength:50,required:true" />
                                    </td>
                                <tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td colspan=3>
                                        <input type="text" placeholder="Account" class="input fl" name="account" size="30" validate="maxlength:50,required:true" />
                                    </td>
                                <tr>
                            </table>
                            <div class="btn hidden">
                                <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}" class="button tutti_hidden_obj" />
                                <input type="reset" value="{pigcms{:L('_BACK_CANCEL_')}" class="button tutti_hidden_obj" />
                            </div>
                        </form>
                        </div>
                        <div id="modal-table" class="map-modal" tabindex="-1" style="display:block;">
                            <div class="map-modal-dialog" style="width:98%;;">
                                <div class="modal-content" style="width:100%;">
                                    <div class="modal-header no-padding" style="width:100%;">
                                        <div class="table-header">
                                            {pigcms{:L('_BACK_DRAG_RED_PIN_')}
                                        </div>
                                    </div>
                                    <div class="modal-body no-padding" style="width:100%;">
                                        <form id="map-search" style="margin:10px;">
                                            <input id="map-keyword" type="textbox" style="width:300px;" placeholder="Enter your address"/>
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
<script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
	<script type="text/javascript">
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
<include file="Public:footer_inner"/>