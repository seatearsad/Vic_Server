<include file="Public:header"/>
	<style>.frame_form td{vertical-align:middle;}
		select{width:80px;}
		textarea{width:300px;height:80px;}
		.textIamge{
			background-image:none!important;
		}
		.wx_coupon{
			<if condition="$coupon['sync_wx'] eq 0 OR  $coupon['wx_cardid'] eq ''">display:none;</if>
		}
		.mini_img{
			width:60px;
			height:30px;
		}
	</style>
	<form id="myform" method="post" action="{pigcms{:U('Coupon/edit')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<input type="hidden" name="coupon_id" value="{pigcms{$coupon.coupon_id}">
			<tr>
				<td width="100">{pigcms{:L('_STORE_PRO_NAME_')}：</td>
				<td>
				{pigcms{$coupon.name}
				</td>
			</tr>
			<tr style="display: none;">
				<td width="100">优惠券图标：</td>
				<td><input type="text"  style="width:200px;" name="img" class="input input-image" value="{pigcms{$coupon.img}"  validate="required:true" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a></td>
				<td>图片尺寸建议 正方形 200 X 200</td>			
			</tr>
			
			<tr style="display: none;">
				<td width="100">微信分享图片：</td>
				<td><input type="text"  style="width:200px;" name="wx_share_img" class="input input-image" value="{pigcms{$coupon.wx_share_img}"  validate="required:true" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a></td>
				<td><font color="red">不上传图片则不能生产二维码</font></td>			
			</tr>
			<tr style="display: none;">
				<td width="100">同步到微信卡券：</td>
				<td colspan="2">
					<if condition="$coupon['sync_wx'] eq 1"><span>是</span><else /><span>否</span></if>
					&nbsp;&nbsp;
					<a href="javascript:void(0)" onclick="window.top.artiframe('{pigcms{:U('Coupon/show')}','微信卡券示例图',300,500,true,false,false,'','show',true);" style="color:blue">微信卡券示例图</a>
				</td>
				
			</tr>
				
			<tr class="wx_coupon">
				<td width="100" style="color:red">创建朋友的券：</td>
				<td >
					<if condition="$coupon['share_friends'] eq 1"><span>是</span><else /><span>否</span></if>
				</td>
				<td>
					选择创建朋友的券后该优惠券不能分享和赠送
				</td>
					
			</tr>
			
			<tr class="wx_coupon">
				<td width="100" style="color:red">卡券颜色</td>
				<td colspan="2">
					<div id="wx_color" style="width:30px;height:30px;background-color:{pigcms{$coupon.color}; float:left;margin-left:10px"></div>
				</td>
					
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">商家名称</td>
				<td colspan="2">
				{pigcms{$coupon.brand_name}
				</td>
					
			</tr>
			
			<tr class="wx_coupon">
				<td width="100" style="color:red">卡券提示</td>
				<td colspan="2">
					{pigcms{$coupon.notice}
				</td>
					
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">卡券副标题</td>
				<td colspan="2">
					{pigcms{$coupon.center_sub_title}
				</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">立即使用链接</td>
				<td colspan="2">
				{pigcms{$coupon.center_url}
				</td>
					
			</tr>
		
			
			<tr class="wx_coupon">
				<td width="100" style="color:red">更多优惠链接</td>
				<td colspan="2">
					{pigcms{$coupon.promotion_url}
				</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">自定义链接</td>
				<td colspan="2">
					标题：{pigcms{$coupon.custom_url_name}<br><br>
					链接：{pigcms{$coupon.custom_url}<br><br>
					副标题：{pigcms{$coupon.custom_url_sub_title}
				</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">封面图片</td>
				<td colspan="3"><img class="mini_img" src="{pigcms{$coupon.icon_url_list}">&nbsp;&nbsp; 描述 :pigcms{$coupon.abstract}</td>
			</tr>
			<tr class="wx_coupon">
				<td width="100" style="color:red">商家服务类型</td>
				<td colspan="2">
					<volist name="coupon.business_service" id="vo">
						<if condition="$vo eq 'BIZ_SERVICE_DELIVER'">
						外卖服务&nbsp;&nbsp;
						<elseif condition="$vo eq 'BIZ_SERVICE_FREE_PARK'" />
						停车位&nbsp;&nbsp;
						<elseif condition="$vo eq 'BIZ_SERVICE_WITH_PET'" />
						可带宠物&nbsp;&nbsp;
						<elseif condition="$vo eq 'BIZ_SERVICE_FREE_WIFI'" />
						免费wifi&nbsp;&nbsp;
						</if>
					</volist>
					
				</td>
			</tr>
			<tr class="wx_coupon">
				<td colspan="3">
				<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
					<volist name="coupon.text_image_list" id="vo">
					<tr class="plus textIamge" >
						<td width="60" style="color:red">卡券图文<label>{pigcms{$i}</label></td>
						<td>
							<table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
								<tr class="textIamge">
									<td width="36" style="color:red">图片：</td>
									<td><img class="mini_img" src="{pigcms{$vo.image_url}"></td>
									<td width="36" style="color:red">描述：</td>
									<td>
									{pigcms{$vo.text}
									</td>
									<td rowspan="2" class="delete">
									
									</td>
								<tr/>
								
							</table>
						</td>
					</tr>
					</volist>
					<tr class="textIamge">
						
					</tr>
					
					
				</table>
				</td>
			</tr>
		
			<tr>
				<td width="100">{pigcms{:L('_BACK_ONLY_NEW_USER_')}：</td>
				<td>
					<if condition="$coupon['allow_new'] eq 1">Yes<elseif condition="$coupon['allow_new'] eq 0"/>No</if>
				</td>
				
			</tr>
			<tr>
				<td width="100">{pigcms{:L('_BACK_USE_PLAT_')}：</td>
				<td>
				{pigcms{$coupon.platform}
				</td>
			</tr>
			<!--tr>
				<td width="100">{pigcms{:L('_BACK_USE_CATE_')}：</td>
				<td>
				{pigcms{$coupon.cate_name}
				</td>
				
			</tr-->
			<tr style="display:none">
				<td width="100">使用分类：</td>
				<td id="cate_id">
				{pigcms{$coupon.cate_id}
				</td>
			</tr>
			<tr style="display: none;">
				<td width="100">微信展示简短描述(微信卡包优惠说明)：</td>
				<td>
				<textarea name="des" value=""  autocomplete="off" validate="required:true">{pigcms{$coupon.des}</textarea>
				</td>
			</tr>
			<tr style="display: none;">
				<td width="100">领取页面详细描述(微信卡包使用须知)：</td>
				<td>
				<textarea name="des_detial" value=""  autocomplete="off" validate="required:true">{pigcms{$coupon.des_detial}</textarea>
				</td>
				<td>每条描述请换行</td>
			</tr>
			<tr>
				<td width="100">{pigcms{:L('_BACK_QUANTITY_')}：{pigcms{$coupon.now_num}</td>
				<td width="85%" colspan="3">
				
				<input type="hidden" name="status" value="{pigcms{$coupon.status}"/>
				<input type="hidden" name="had_pull" value="{pigcms{$coupon.had_pull}"/>
				<input type="hidden" name="num" value="{pigcms{$coupon.now_num}"/>
				<select name="add" class="fl">
					<option value="0">Add</option>
					<option value="1">Reduce</option>
				</select>
				<input type="text" class="input fl" style="margin-left:4px;" name="num_add" value=""  autocomplete="off" validate="digits:true,min:1">{pigcms{:replace_lang_str(L('_BACK_ALREADY_CLAIM_'),$coupon['had_pull'])}
				</td>
			</tr>
			
			<!--tr>
				<td width="100">{pigcms{:L('_BACK_PICK_NUM_LIM_')}：</td>
				<td>
				{pigcms{$coupon.limit}
				</td>
			</tr>
			<tr>
				<td width="100">{pigcms{:L('_BACK_USE_NUM_LIM_')}：</td>
				<td>
				{pigcms{$coupon.use_limit}
				</td>
			</tr-->
			<tr>
				<td width="100">{pigcms{:L('_BACK_DIS_PRICE_')}：</td>
				<td>
				{pigcms{$coupon.discount}
				</td>
			</tr>
			<tr>
				<td width="100">{pigcms{:L('_BACK_MIN_PRICE_')}：</td>
				<td>
				{pigcms{$coupon.order_money}
				</td>
			</tr>
			<tr>
				<td width="100">{pigcms{:L('_BACK_PERIOD_')}：</td>
				<td>
					{pigcms{$coupon.start_time|date='Y-m-d',###}——{pigcms{$coupon.end_time|date='Y-m-d',###}
				</td>
			</tr>
            <if condition="$system_session['level'] neq 3">
                <tr>
                    <td width="100">{pigcms{:L('G_UNIVERSAL')}</td>
                    <td colspan="2">
                        <span class="cb-enable"><label <if condition="$coupon['city_id'] eq 0">class="cb-enable selected"<else />class="cb-enable"</if>><span>{pigcms{:L('G_UNIVERSAL')}</span><input id="yes" type="radio" name="currency" value="1" <if condition="$coupon['city_id'] eq 0">checked="checked"</if> /></label></span>
                        <span class="cb-disable"><label <if condition="$coupon['city_id'] eq 0">class="cb-disable"<else />class="cb-disable selected"</if> ><span>{pigcms{:L('G_CITY_SPECIFIC')}</span><input id="no" type="radio" name="currency" value="2" <if condition="$coupon['city_id'] neq 0">checked="checked"</if> /></label></span>
                    </td>
                </tr>
                <tr id="adver_region" <if condition="$coupon['city_id'] eq 0">style="display:none;"</if>>
                    <td width="100">{pigcms{:L('_B_PURE_MY_13_')}</td>
                    <td colspan="2" id="choose_cityareass" province_idss="" city_idss="{pigcms{$coupon.city_id}"></td>
                </tr>
                <else />
                <tr>
                    <td width="100">{pigcms{:L('_B_PURE_MY_13_')}：</td>
                    <td colspan="2">
                        {pigcms{$city['area_name']}
                        <input type="hidden" name="city_id" value="{pigcms{$city['area_id']}">
                    </td>
                </tr>
            </if>
			<if condition="($coupon.status eq 0) OR ($coupon.status eq 1)">
			<tr>
				<td width="100">{pigcms{:L('_BACK_STATUS_')}</td>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$coupon['status'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ACTIVE_')}</span><input type="radio" name="status" value="1" <if condition="$coupon['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$coupon['status'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0" <if condition="$coupon['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			</if>
            <tr>
                <td width="100">{pigcms{:L('_BACK_PICK_KEY_')}：</td>
                <td>
                    {pigcms{$coupon['notice']}
                </td>
            </tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
		
	</form>
	<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>

	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript">
        KindEditor.ready(function (K) {
            var site_url = "{pigcms{$config.site_url}";
            var editor = K.editor({
                allowFileManager: true
            });
            $('.J_selectImage').click(function () {
                var upload_file_btn = $(this);
                editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
                editor.loadPlugin('image', function () {
                    editor.plugin.imageDialog({
                        showRemote: false,
                        clickFn: function (url, title, width, height, border, align) {
                            upload_file_btn.siblings('.input-image').val(site_url + url);
                            editor.hideDialog();
                        }
                    });
                });
            });

        });

        $(document).ready(function () {

            $('select[name="color"]').css('background-color', '#63b359');
            $('select[name="color"]').change(function (event) {
                $('#wx_color').css('background-color', $('select[name="color"]').find('option:selected').html());
                $(this).css('background-color', $('select[name="color"]').find('option:selected').html());
            });

            $('input:radio[name="sync_wx"]').click(function (i, val) {
                if ($(this).val() == 1) {
                    $('.wx_coupon').show();
                } else {
                    $('.wx_coupon').hide();
                }
            });

        });

        function plus() {
            var item = $('.plus:last');
            var newitem = $(item).clone(true);
            var No = parseInt(item.find("label").html()) + 1;
            $('.delete').children().show();
            if (No > 4) {
                alert('不能超过4条信息');
            } else {
                $(item).after(newitem);
                newitem.find('input').attr('value', '');
                newitem.find('textarea').attr('value', '');
                newitem.find("#addLink").attr('onclick', "addLink('url" + No + "',0)");
                newitem.find("label").html(No);
                newitem.find('input[name="url[]"]').attr('id', 'url' + No);
                newitem.find('.delete').children().show();
            }
        }

        function del(obj) {
            if ($('.plus').length <= 1) {
                $('.delete').children().hide();
            } else {
                if ($('.plus').length == 2) {
                    $('.delete').children().hide();
                }
                $(obj).parents('.plus').remove();
                $.each($('.plus'), function (index, val) {
                    var No = index + 1;
                    $(val).find('label').html(No);
                    $(val).find('input[name="url[]"]').attr('id', 'url' + No);
                    $(val).find("#addLink").attr('onclick', "addLink('url" + No + "',0)");
                });
            }
        }

        $("#yes").click(function () {
            $("#adver_region").hide();
        })
        $("#no").click(function () {
            $("#adver_region").show();
        })
    </script>

	
<include file="Public:footer"/>

