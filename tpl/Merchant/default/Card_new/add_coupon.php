<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card_new/index')}">会员卡</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Card_new/card_new_coupon')}">优惠券列表</a></li>
			<li class="active">添加优惠券</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post" action="{pigcms{:U('Card_new/add_coupon')}">
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">优惠券名称</label></label>
									<input type="text" class="col-sm-3" name="name" value="{pigcms{$coupon.info}" />
									
									<span class="form_tips">（微信要求9个汉字以内）</span>
								</div>
								
								<div class="form-group" style="margin-bottom:-35px;">
									<label class="col-sm-3"><label for="AutoreplySystem_img">优惠券展示图片</label></label>
								</div>
									
								<div class="form-group" style="width:417px;padding-left:140px;">
									<label class="ace-file-input">
										<input class="col-sm-4" id="ace-file-input" size="50" onchange="preview1(this,'img')" name="img" type="file">
										<span class="ace-file-container" data-title="选择">
											<span class="ace-file-name" data-title="上传图片..."><i class=" ace-icon fa fa-upload"></i></span>
										</span>
									</label>
									<div id="flash_preview1"><img style="width:100px;height:50px" id="img" src="{pigcms{$info.img}"></div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">是否与商家会员卡优惠同时使用</label>
									<select name="use_with_card" >
										<option value="0" selected = 'selected'>否</option>
										<option value="1" >是</option>
									</select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">是否领卡时自动领取</label>
									<select name="auto_get" >
										<option value="0" >否</option>
										<option value="1" >是</option>
									</select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">同步到微信卡券</label>
									<select name="sync_wx" >
										<option value="0" selected = 'selected'>否</option>
										<option value="1" >是</option>
									</select>
									<a href="{pigcms{:U('Card_new/show')}" class="see_qrcode">查看微信卡券示例</a>
									选择创建朋友的券后该优惠券不能分享和赠送
								</div>
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">卡券颜色</label>
									<select name="color" id="color" style="float:left;width:100px;" >
										<volist name="color_list" id="vo">
											<option value="{pigcms{$key}" style="background-color:{pigcms{$vo};margin:5px auto;">{pigcms{$vo}</option>
										</volist>
									</select>
									<div id="wx_color" style="width:30px;height:30px;background-color:#63b359; float:left;margin-left:10px"></div>
								</div>
									<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">商家名称</label>
									<input type="text"  style="width:180px;" name="brand_name" class="input input-image" value="" >（12个汉字以内）
								</div>
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">卡券提示</label>
									<input type="text"  style="width:180px;" name="notice" class="input input-image" value="" >（16个汉字以内）
								</div>
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">卡券副标题</label>
									<input type="text"  style="width:180px;" name="center_sub_title" class="input input-image" value="" >（副标题6个汉字以内）
									
								</div>
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">立即使用链接</label>
									
									<input type="text"  style="width:180px;" name="center_url" class="input input-image" value="" >
									
										
								</div>
							
								
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">更多优惠链接</label>
									
									<input type="text"  style="width:180px;" name="promotion_url" class="input input-image" value="" >（如：立即使用）
									
								</div>
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">自定义链接</label>
									
										标题：<input type="text"  style="width:100px;" name="custom_url_name" class="input input-image" value="" >（5个汉字以内）
										链接：<input type="text"  style="width:180px;" name="custom_url" class="input input-image" value="" >
										副标题：<input type="text"  style="width:180px;" name="custom_url_sub_title" class="input input-image" value="" >（副标题6个汉字以内）
									
								</div>
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">封面图片</label>
									<input type="text"  style="width:200px;" name="icon_url_list" class="input input-image" value=""   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a> 描述 :<input type="text"  style="width:200px;" name="abstract" class="input" value="" >
								</div>
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">商家服务类型</label>
									
										<input type="checkbox"  name="business_service[]" class="input input-image" value="BIZ_SERVICE_DELIVER" checked="checked">外卖服务
										<input type="checkbox"  name="business_service[]" class="input input-image" value="BIZ_SERVICE_FREE_PARK" checked="checked">停车位
										<input type="checkbox"  name="business_service[]" class="input input-image" value="BIZ_SERVICE_WITH_PET" checked="checked">可带宠物
										<input type="checkbox"  name="business_service[]" class="input input-image" value="BIZ_SERVICE_FREE_WIFI" checked="checked">免费wifi
									
								</div>
								
								<div class="form-group wx_coupon">
									<label class="col-sm-1" style="color:red">卡券图文</label>
									<table cellpadding="0" cellspacing="0" class="" width="60%">
					
										<tr class="plus textIamge" >
											<td width="100" style="color:red">卡券图文<label>1</label></td>
											<td>
												<table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
													<tr class="textIamge">
														<td width="66" style="color:red">图片：</td>
														<td width="300"><input type="text"  style="width:120px;" name="image_url[]" class="input input-image" value=""   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage"  style="background: #87b87f!important;border-color: #87b87f;color:#fff;">上传图片</a></td>
														<td width="66" style="color:red">描述：</td>
														<td width="180"><textarea  style="width:150px;height:60px" class="input" name="text[]" ></textarea></td>
														<td rowspan="2" class="delete">
															<a href="javascript:void(0)" onclick="del(this)">[删除]</a>
														</td>
													<tr/>
													
												</table>
											</td>
										</tr>
										<tr class="textIamge">
											<td></td>
											<td><a href="javascript:void(0)" onclick="plus()"><img style="width:20px;height:20px;" src="{pigcms{$static_path}images/plus.jpg"/></a></td>
										</tr>
										
										
									</table>
								</div>
							
								<div class="form-group">
									<label class="col-sm-1">是否只允许新用户使用</label>
									<select name="allow_new" >
										<option value="0"  <if condition="$coupon.allow_new eq '0'">selected = 'selected'</if>>否</option>
										<option value="1"  <if condition="$coupon.allow_new eq '1'">selected = 'selected'</if>>是</option>
									</select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">使用平台</label>
									<volist name="platform" id="vo">
										<input type="checkbox" name="platform[]" value="{pigcms{$key}">{pigcms{$vo}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</volist>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">使用类别</label>
									<select name="cate_name">
										<option value="all">全选</option>
										<volist name="category" id="vo">
											<if condition="$key neq 'appoint' || isset($config['appoint_page_row'])">
												<option value="{pigcms{$key}">{pigcms{$vo}</option>
											</if>
										</volist>
									</select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" >使用分类</label>
									<div id="cate_id">
									</div>
								</div>
							
								<div class="form-group">
									<label class="col-sm-1" >展示简短描述</label>
									<textarea name="des" cols="56" rows="8" value=""  autocomplete="off" validate="required:true,maxlength:30"></textarea>(微信卡券优惠说明)
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" >使用说明</label>
									<textarea name="des_detial" cols="56" rows="8"  value=""  autocomplete="off" validate="required:true"></textarea>
									<span class="form_tips">每条描述请换行,微信卡券使用须知</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">数量</label></label>
									<input type="text" class="col-sm-3" name="num" value="" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">领取数量限制</label></label>
									<input type="text" class="col-sm-3" name="limit" value="" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">使用数量限制</label></label>
									<input type="text" class="col-sm-3" name="use_limit" value="" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">优惠金额</label></label>
									<input type="text" class="col-sm-3" name="discount" value="" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">最小订单金额</label></label>
									<input type="text" class="col-sm-3" name="order_money" value="" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">起始时间</label></label>
									<input type="text" class="input-text" name="start_time" style="width:120px;" id="d4311"  value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>-
									<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" />
								</div>
						
								
								<div class="form-group">
									<label class="col-sm-1">状态</label>
									<select name="status" >
										<option value="0">禁止</option>
										<option value="1" selected = 'selected'>正常</option>
									</select>
								</div>
							
													
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit">
											<i class="ace-icon fa fa-check bigger-110"></i>
											保存
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
.wx_coupon{
	display:none;
}
</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
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
function preview1(input,img){
	if (input.files && input.files[0]){

		var reader = new FileReader();
		reader.onload = function (e) { $('#'+img).attr('src', e.target.result);}
		reader.readAsDataURL(input.files[0]);
	}
}

function viewTpl(){
	var tid = $('#tpid').val();
	chooseTpl(tid,'',2);
}

function viewTpl2(){
	var tid = $('#conttpid').val();
	chooseTpl(tid,'',4);
}
</script>


<script type="text/javascript">
	 
		$(document).ready(function() {
			var post_url = "{pigcms{:U('Card_new/ajax_ordertype_cateid')}";
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
				
				
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看示例',
				padding: 0,
				width: 680,
				height: 620,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
			
			$('select[name="color"]').css('background-color','#63b359');	
			$('select[name="color"]').change(function(event) {
				$('#wx_color').css('background-color',$('select[name="color"]').find('option:selected').html());
				$(this).css('background-color',$('select[name="color"]').find('option:selected').html());
			});		

			$('select[name="sync_wx"]').change(function(i,val){
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
		
	</script>
<include file="Public:footer"/>