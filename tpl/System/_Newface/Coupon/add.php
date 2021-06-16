<include file="Public:header"/>
	<style>.frame_form td{vertical-align:middle;}
		select{width:80px;}
		textarea{width:300px;height:80px;}
		
		.textIamge{
			background-image:none!important;
		}
		.wx_coupon{
			display:none;
		}
		.ke-dialog{
			top:10px;
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
                        <div>
                            <form id="myform" method="post" action="{pigcms{:U('Coupon/add')}" frame="true" refresh="true">
                                <input type="hidden" class="input fl" name="limit" value="1"  autocomplete="off" validate="required:true,digits:true,min:1">
                                <input type="hidden" class="input fl" name="use_limit" value="1"  autocomplete="off" validate="required:true,digits:true,min:1">

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_STORE_PRO_NAME_')}</label>
                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control" class="input fl" name="name" value=""  validate="required:true" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="form-group row wx_coupon">
                                    <label class="col-sm-3 col-form-label">卡券颜色</label>
                                    <div class="col-sm-9">
                                        <select name="color" class="form-control" id="color" style="float:left;width:100px;" >
                                            <volist name="color_list" id="vo">
                                                <option value="{pigcms{$key}" style="background-color:{pigcms{$vo};margin:5px auto;">{pigcms{$vo}</option>
                                            </volist>
                                        </select>
                                        <div id="wx_color" style="width:30px;height:30px;background-color:#63b359; float:left;margin-left:10px"></div>
                                    </div>
                                </div>

                                <div class="form-group row wx_coupon">
                                    <label class="col-sm-3 col-form-label">商家名称</label>
                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control" name="brand_name" class="input input-image" value="" >（12个汉字以内）
                                    </div>
                                </div>

                                <div class="form-group  row wx_coupon">
                                    <label class="col-sm-3 col-form-label">卡券提示</label>
                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control" name="notice" class="input input-image" value="" >（16个汉字以内）
                                    </div>
                                </div>

                                <div class="form-group  row wx_coupon">
                                    <label class="col-sm-3 col-form-label">卡券副标题</label>
                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control" name="center_sub_title" class="input input-image" value="" >（副标题6个汉字以内）
                                    </div>
                                </div>

                                <div class="form-group  row wx_coupon">
                                    <label class="col-sm-3 col-form-label">立即使用链接</label>
                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control"  name="center_url" class="input input-image" value="" >
                                    </div>
                                </div>

                                <div class="form-group  row wx_coupon">
                                    <label class="col-sm-3 col-form-label">更多优惠链接</label>
                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control" name="promotion_url" class="input input-image" value="" >
                                    </div>
                                </div>

                                <div class="form-group  row wx_coupon">
                                    <label class="col-sm-3 col-form-label">自定义链接</label>
                                    <div class="col-sm-9">
                                        标题：<input type="text" class="form-control"  name="custom_url_name" class="input input-image" value="" >（5个汉字以内）<br><br>
                                        链接：<input type="text"  class="form-control" style="margin-top:10px;" name="custom_url" class="input input-image" value="" ><br><br>
                                        副标题：<input type="text"  class="form-control" style="margin-top:10px;" name="custom_url_sub_title" class="input input-image" value="" >（副标题6个汉字以内）
                                    </div>
                                </div>
                                <div class="form-group  row wx_coupon">
                                    <label class="col-sm-3 col-form-label">封面图片</label>
                                    <div class="col-sm-9">
                                        <input type="text"  name="icon_url_list" class="form-control" value="" readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a> 描述 :<input type="text" class="form-control" name="abstract" class="input" value="" >
                                    </div>
                                </div>
                                <div class="form-group  row wx_coupon">
                                    <label class="col-sm-3 col-form-label">商家服务类型</label>
                                    <div class="col-sm-9">
                                        <input type="checkbox"  name="business_service[]" class="form-control" value="BIZ_SERVICE_DELIVER" checked="checked">外卖服务
                                        <input type="checkbox"  name="business_service[]" class="form-control" value="BIZ_SERVICE_FREE_PARK" checked="checked">停车位
                                        <input type="checkbox"  name="business_service[]" class="form-control" value="BIZ_SERVICE_WITH_PET" checked="checked">可带宠物
                                        <input type="checkbox"  name="business_service[]" class="form-control" value="BIZ_SERVICE_FREE_WIFI" checked="checked">免费wifi
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_ONLY_NEW_USER_')}</label>
                                    <div class="col-sm-9">
                                        <span class="cb-enable"><label class="cb-enable <if condition="$coupon['allow_new'] eq 1"> selected</if>"><span>Yes</span><input type="radio" name="allow_new" value="1" <if condition="$coupon['allow_new'] eq 1"> checked="checked"</if>/></label></span>
                                        <span class="cb-disable"><label class="cb-disable <if condition="$coupon['allow_new'] eq 0"> selected</if>"><span>No</span><input type="radio" name="allow_new" value="0" <if condition="$coupon['allow_new'] eq 0"> checked="checked"</if>/></label></span>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_USE_PLAT_')}</label>
                                    <div class="col-sm-9">
                                        <volist name="platform" id="vo">
                                            <input type="checkbox" class="input input-image" style="margin-bottom:10px;" name="platform[]" value="{pigcms{$key}" checked="checked">{pigcms{$vo}
                                        </volist>
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_QUANTITY_')}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="num" value=""  autocomplete="off" validate="required:true,digits:true,min:1">
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_DIS_PRICE_')}</label>
                                    <div class="col-sm-9">
                                       <input type="text" class="form-control" name="discount" value=""  autocomplete="off" validate="required:true,number:true,min:0.01">
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MIN_PRICE_')}</label>
                                    <div class="col-sm-9">
                                       <input type="text" class="form-control" name="order_money" value=""  autocomplete="off" validate="required:true,number:true,min:0.01">
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_PERIOD_')}</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="start_time"  id="d4311"  value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>-

                                    </div>
                                    <div class="col-sm-1" style="text-align: center">至</div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="end_time" id="d4311" value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})" />
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>

                                <if condition="$system_session['level'] neq 3">
                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('G_UNIVERSAL')}</label>
                                        <div class="col-sm-9">
                                            <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('G_UNIVERSAL')}</span><input id="yes" type="radio" name="currency" value="1" checked="checked" /></label></span>
                                            <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('G_CITY_SPECIFIC')}</span><input id="no" type="radio" name="currency" value="2" /></label></span>
                                        </div>
                                    </div>
                                    <div class="form-group  row" id="adver_region" style="display:none;">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_B_PURE_MY_13_')}</label>
                                        <div class="col-sm-9" id="choose_cityareass" province_idss="" city_idss="">

                                        </div>
                                    </div>
                                <else />
                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_B_PURE_MY_13_')}</label>
                                        <div class="col-sm-9">
                                            {pigcms{$city['area_name']}
                                            <input type="hidden" name="city_id" class="form-control" value="{pigcms{$city['area_id']}">
                                        </div>
                                    </div>
                                </if>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STATUS_')}</label>
                                    <div class="col-sm-9">
                                        <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('_BACK_ACTIVE_')}</span><input type="radio" name="status" value="1" checked="checked"/></label></span>
                                        <span class="cb-disable"><label class="cb-disable "><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0" /></label></span>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_PICK_KEY_')}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="input fl" style="margin-left:4px;" name="notice" value=""  autocomplete="off">
                                    </div>
                                </div>

                                <div class="btn tutti_hidden_obj">
                                    <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}" class="button" />
                                    <input type="reset" value="{pigcms{:L('_BACK_CANCEL_')}" class="button" />
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
	<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>

	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
				var site_url = "{pigcms{$config.site_url}";
				var editor = K.editor({
					allowFileManager : true
				});
				$('.J_selectImage').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
					editor.loadPlugin('image', function(){
						editor.plugin.imageDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-image').val(site_url+url);
								editor.hideDialog();
							}
						});
					});
				});

			});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			var post_url = "{pigcms{:U('Coupon/ajax_ordertype_cateid')}";
				$('select[name="cate_name"]').change(function(event) {
					var order_type=$(this).val();
					if(order_type!='all'){
						
					$.ajax({
						url: post_url,
						type: 'POST',
						dataType: 'json',
						data: {order_type: order_type},
						success:function(date){
							$('#cate_id').html('<select name="cate_id" id="'+order_type+'"><option value="0">全选</option></select>');
							$.each(date, function(index, val) {
								$('#'+order_type).append('<option value="'+val.cat_id+'">'+val.cat_name+'</option>');
							});
						}
					});
					}else{
						$('#cate_id').empty();
					}
					
					
				});
			
			$('select[name="color"]').css('background-color','#63b359');	
			$('select[name="color"]').change(function(event) {
				$('#wx_color').css('background-color',$('select[name="color"]').find('option:selected').html());
				$(this).css('background-color',$('select[name="color"]').find('option:selected').html());
			});		

			$('input:radio[name="sync_wx"]').click(function(i,val){
				if($(this).val()==1){
					$('.wx_coupon').show();
				}else{
					$('.wx_coupon').hide();
				}
			});
			
		});
		
		function plus(){
			var item = $('.plus:last');
			var newitem = $(item).clone(true);
			var No = parseInt(item.find("label").html())+1;
			$('.delete').children().show();
			if(No>4){
				alert('不能超过4条信息');
			}else{
				$(item).after(newitem);
				newitem.find('input').attr('value','');
				newitem.find('textarea').attr('value','');
				newitem.find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				newitem.find("label").html(No);
				newitem.find('input[name="url[]"]').attr('id','url'+No);
				newitem.find('.delete').children().show();
			}
		}
		function del(obj){
			if($('.plus').length<=1){
				$('.delete').children().hide();
			}else{
				if($('.plus').length==2){
					$('.delete').children().hide();
				}
				$(obj).parents('.plus').remove();
				$.each($('.plus'), function(index, val) {
					var No =index+1;
					$(val).find('label').html(No);
					$(val).find('input[name="url[]"]').attr('id','url'+No);
					$(val).find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				});
			}
		}

        $("#yes").click(function(){
            $("#adver_region").hide();
        })
        $("#no").click(function(){
            $("#adver_region").show();
        })
	</script>

            <include file="Public:footer_inc"/>
