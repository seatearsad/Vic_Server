<include file="Public:header"/>
<style>
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
    #J_selectImage_0,#J_selectImage_1,#J_selectImage_2{
        background-color: #ffa52d;
        color:white;
        text-indent: 0px;
        border-radius: 5px;
        padding: 0px;
        height: 50px;
        line-height: 50px;
        box-sizing: border-box;
        display: inline-block;
        width: 100%;
    }
    .img_0,.img_1,.img_2{
        width: 100%;
        text-align: center;
    }
    .img_0 img,.img_1 img,.img_2 img{
        height: 100px;
    }
    .btn{
        background:none;
        padding:0 12px;
    }
</style>
	<form id="myform" method="post" action="{pigcms{:U('Deliver/user_view')}" frame="true" refresh="true">
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
				<td width="35%"><input type="text" class="input fl" name="pwd" size="20" value="" tips="不修改则不填写" /></td>
				<th width="15%">{pigcms{:L('_BACK_STATUS_')}</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_user['status'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_NORMAL_')}</span><input type="radio" name="status" value="1"  <if condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_user['status'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0"  <if condition="$now_user['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
			 	<th width="15%">{pigcms{:L('_BACK_DELIVER_AREA_')}</th>
				<!--td id="choose_cityarea" colspan=3  province_id="{pigcms{$now_user.province_id}" city_id="{pigcms{$now_user.city_id}" area_id="{pigcms{$now_user.area_id}" circle_id="{pigcms{$now_user.circle_id}"></td-->
                <td id="city_name">
                    {pigcms{$now_user['city_name']}
                </td>
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
				<td width="85%" colspan=3><input type="text" size="50" class="input fl" name="adress" id="adress" validate="required:true" value="{pigcms{$now_user.site}"/></td>
            </tr>
            <tr>
				<th width="15%">{pigcms{:L('_BACK_COURIER_LOC_')}</th>
				<td width="85%" colspan=3><input class="input fl" size="20" name="long_lat" id="long_lat" type="text" readonly="readonly" validate="required:true" value="{pigcms{$now_user.lng},{pigcms{$now_user.lat}"/></td>
			</tr>
            <tr>
                <th width="15%">SIN Number</th>
                <td colspan=3>
                    <input type="text" placeholder="SIN Number" class="input fl" name="sin_num" size="30" validate="maxlength:50,required:true" value="{pigcms{$img.sin_num}" />
                </td>
            </tr>
            <tr>
                <th width="15%">{pigcms{:L('_BACK_BANK_INFO_')}</th>
                <td colspan=3>
                    <input type="text" placeholder="Account Holder Name" class="input fl" name="ahname" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.ahname}" />
                </td>
            </tr>
            <tr>
                <th width="15%"></th>
                <td colspan=3>
                    <input type="text" placeholder="Transit(Branch)" class="input fl" name="transit" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.transit}" />
                </td>
            </tr>
            <tr>
                <th width="15%"></th>
                <td colspan=3>
                    <input type="text" placeholder="Institution" class="input fl" name="institution" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.institution}" />
                </td>
            </tr>
            <tr>
                <th width="15%"></th>
                <td colspan=3>
                    <input type="text" placeholder="Account" class="input fl" name="account" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.account}" />
                </td>
            </tr>
            <if condition="$now_user['reg_status'] gt 1">
            <tr>
                <th width="15%">{pigcms{:L('_BACK_DRIVER_LIC_')}</th>
                <td colspan=3>
                    <div style="display:inline-block;" id="J_selectImage_0">
                        <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                            {pigcms{:L('_ND_UPLOAD1_')}
                        </div>
                    </div>
                    <if condition="$img['driver_license'] eq ''">
                        <div class="img_0">

                        </div>
                    <else />
                        <div class="img_0" style="height: 100px">
                            <img src="{pigcms{:C('config.site_url')}{pigcms{$img['driver_license']}" height="100"/>
                        </div>
                    </if>
                </td>
            </tr>
            <tr>
                <th width="15%">{pigcms{:L('_BACK_VEHICLE_INSU_')}</th>
                <td colspan=3>
                    <div style="display:inline-block;" id="J_selectImage_1">
                        <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                            {pigcms{:L('_ND_UPLOAD2_')}
                        </div>
                    </div>
                    <if condition="$img['insurance'] eq ''">
                        <div class="img_1">

                        </div>
                        <else />
                        <div class="img_1" style="height: 100px">
                            <img src="{pigcms{:C('config.site_url')}{pigcms{$img['insurance']}" height="100"/>
                        </div>
                    </if>
                </td>
            </tr>
            <tr>
                <th width="15%">{pigcms{:L('_BACK_PROOF_WORK_')}</th>
                <td colspan=3>
                    <div style="display:inline-block;" id="J_selectImage_2">
                        <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                            {pigcms{:L('_ND_UPLOAD3_')}
                        </div>
                    </div>
                    <if condition="$img['certificate'] eq ''">
                        <div class="img_2">

                        </div>
                        <else />
                        <div class="img_2" style="height: 100px">
                            <img src="{pigcms{:C('config.site_url')}{pigcms{$img['certificate']}" height="100"/>
                        </div>
                    </if>
                </td>
            </tr>
            </if>
            <if condition="$now_user['group'] neq 1">
            <tr>
                <th width="15%">{pigcms{:L('_BACK_WHETHER_PASS_')}</th>
                <td colspan=3>
                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('_BACK_PASS_REVIEW_')}</span><input type="radio" name="review" value="1" checked="checked" /></label></span>
                    <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('_BACK_NO_PASS_REVIEW_')}</span><input type="radio" name="review" value="0" /></label></span>
                </td>
            </tr>
                <tr id="review_desc" <if condition="$img['review_desc'] eq ''">style="display: none"</if>>
                <th width="15%">{pigcms{:L('_BACK_REVIEW_DESC_')}</th>
                <td colspan=3>
                    <input type="text" class="input fl" name="review_desc" value="{pigcms{$img['review_desc']}">
                </td>
            </tr>
            </if>
            <if condition="$now_user['reg_status'] eq 4">
                <tr>
                    <th width="15%">{pigcms{:L('_BACK_WHETHER_RECE_')}</th>
                    <td colspan=3>
                        <span class="cb-enable"><label class="cb-enable"><span>Yes</span><input type="radio" name="receive" value="1"  /></label></span>
                        <span class="cb-disable"><label class="cb-disable selected"><span>No</span><input type="radio" name="receive" value="0" checked="checked" /></label></span>
                    </td>
                </tr>
            </if>
		</table>
        <input type="hidden" name="city_id" id="city_id" value="{pigcms{$now_user['city_id']}">
        <input type="hidden" name="province_id" id="province_id" value="{pigcms{$now_user['province_id']}">
        <input type="hidden" name="driver_license" id="filename_0" value="{pigcms{$img['driver_license']}">
        <input type="hidden" name="insurance" id="filename_1" value="{pigcms{$img['insurance']}">
        <input type="hidden" name="certificate" id="filename_2" value="{pigcms{$img['certificate']}">
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}" class="button" />
			<input type="reset" value="{pigcms{:L('_BACK_CANCEL_')}" class="button" />
		</div>
	</form>
	<!--div id="modal-table" class="modal fade" tabindex="-1" style="display:block;">
		<div class="modal-dialog" style="width:80%;">
			<div class="modal-content" style="width:100%;">
				<div class="modal-header no-padding" style="width:100%;">
					<div class="table-header">
						   拖动红色图标，经纬度框内将自动填充经纬度。
					</div>
				</div>
				<div class="modal-body no-padding" style="width:100%;">
					<form id="map-search" style="margin:10px;">
						<input id="map-keyword" type="textbox" style="width:300px;" placeholder="尽量填写城市、区域、街道名" value="{pigcms{$now_user.site}"/>
						<input type="submit" value="搜索"/>
					</form>
					<div style="width: 650px; height: 250px; min-height: 250px;" id="cmmap"></div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",merchant_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}";
	</script>

	<script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script-->
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en"></script>
<script>
    var  uploader = WebUploader.create({
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

                        $('#filename_'+i).val(file);
                    }
                }
            });
        }

        return img;
    }

    $('img').click(function () {
        //alert($(this).attr('src'));
        window.top.artiframe($(this).attr('src'),'查看',600,500,true,false,false);
    });

    $('input:radio[name=review]').click(function () {
        if($(this).val() == 0){//未通过
            $('#review_desc').show();
        }else{
            $('#review_desc').hide();
        }
    });

    $('#adress').focus(function () {
        initAutocomplete();
    });

    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('adress'), {types: ['geocode'],componentRestrictions: {country: ['ca']}});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        //$("input[name='lng']").val(place.geometry.location.lng());
        //$("input[name='lat']").val(place.geometry.location.lat());
        $('#long_lat').val(place.geometry.location.lng() + ',' +place.geometry.location.lat());

        var add_com = place.address_components;
        console.log(add_com);
        var is_get_city = false;
        for(var i=0;i<add_com.length;i++){
            if(add_com[i]['types'][0] == 'locality'){
                is_get_city = true;
                var city_name = add_com[i]['long_name'];
                $.post("{pigcms{$config.site_url}/index.php?g=Index&c=Index&a=ajax_city_name",{city_name:city_name},function(result){
                    if (result.error == 1){
                        $("input[name='city_id']").val(0);
                        $('#city_name').html('');
                    }else{
                        $("input[name='city_id']").val(result['info']['city_id']);
                        $('#city_name').html(city_name);
                    }
                },'JSON');
            }
        }
    }
</script>
<include file="Public:footer"/>