<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/merchant')}">{pigcms{:L('ACCOUNT_SETTINGS_BKADMIN')}</a>
			</li>
			<li class="active">{pigcms{:L('ACCOUNT_SETTINGS_BKADMIN')}</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">{pigcms{:L('BASIC_SETTING_BKADMIN')}</a>
							</li>
<!--							<li>-->
<!--								<a data-toggle="tab" href="#txtstore">商家描述</a>-->
<!--							</li>-->
							<li>
								<a data-toggle="tab" href="#txtpwd">{pigcms{:L('CHANGE_PASSWORD_BKADMIN')}</a>
							</li>
<!--							<li>-->
<!--								<a data-toggle="tab" href="#bindUser">管理账号</a>-->
<!--							</li>-->
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label>{pigcms{:L('MERCHANT_USERNAME_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.account}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>{pigcms{:L('MERCHANT_NAME_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.name}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">{pigcms{:L('STORE_PHONE_NUMBER_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" name="phone" value="{pigcms{$now_merchant.phone}" id="phone" type="text"/>
									<span class="form_tips">{pigcms{:L('SPLIT_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="email">{pigcms{:L('STORE_EMAIL_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" name="email" value="{pigcms{$now_merchant.email}" id="email" type="text"/>
									<span class="form_tips"></span>
								</div>
								<div class="form-group hidden_obj">
									<label class="col-sm-1"><label>{pigcms{$config.group_alias_name}快递收货超时时间</label></label>
									<input class="col-sm-2" size="20" name="group_express_outtime" value="{pigcms{$now_merchant.group_express_outtime}" type="text"/><span class="form_tips">0为永不超时，1为一天后超时并确认消费。（店员发货后开始计时）</span>
								</div>
								<if condition="$pay_offline_open eq 0 AND $now_merchant.is_close_offline eq 1">
									<div class="form-group hidden_obj">
										<label class="col-sm-1">线下支付的权限</label>
										<label class="col-sm-2" style="color: red;">系统禁止您使用线下支付的权限</label>
									</div>
								<elseif condition="$pay_offline_open eq 1" />
									<div class="form-group hidden_obj">
										<label class="col-sm-1" for="is_offline">线下支付的权限</label>
										<select name="is_offline" id="is_offline">
											<option value="0" <if condition="$now_merchant.is_offline eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$now_merchant.is_offline eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								</if>
								<div class="form-group hidden_obj">
									<label class="col-sm-1"><label>微官网点击量</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.hits}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1">查看二维码</label>
									<div style="padding-top:4px;line-height:24px;"><a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=merchant&id={pigcms{$now_merchant.mer_id}" class="see_qrcode">查看二维码</a></div>
								</div-->
							</div>
							<div id="txtstore" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">商家描述</label>
									<textarea class="col-sm-5" rows="5" name="txt_info">{pigcms{$now_merchant.txt_info}</textarea>
								</div>
								<div class="form-group">
									<label class="col-sm-1">商家图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									<span class="form_tips">第一张将作为主图片！最多上传10个图片！图片宽度建议为700px，高度建议为420px。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_merchant['pic']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
							</div>
							<div id="txtpwd" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1"><label for="email">{pigcms{:L('ORIGINAL_PASSWORD_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" name="old_pass" type="password"/>
									<span class="form_tips">{pigcms{:L('OPTIONAL1_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="email">{pigcms{:L('NEW_PASSWORD_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" name="new_pass" type="password"/>
									<span class="form_tips">{pigcms{:L('6_CHARACTERS_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="email">{pigcms{:L('COMFIRM_NEW_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" name="re_pass" type="password"/>
									<span class="form_tips">{pigcms{:L('PASSWORD_ENSURE_BKADMIN')}</span>
								</div>
							</div>
							<div id="bindUser" class="tab-pane">
								<div class="form-group" id="has_user" <if condition="!$user">style="display:none"</if>>
									<label class="col-sm-1"><label for="email">管理用户</label></label>
									<label class="col-sm-1"><label for="email" id="bind_usernickname">{pigcms{$user['nickname']}</label></label>
									<a class="form_tips" id="delete_bind">解除绑定</a>
								</div>
								<div class="form-group" id="no_user" <if condition="$user">style="display:none"</if>>
									<label class="col-sm-1"><label for="email">绑定用户</label></label>
									<label class="col-sm-1"><a href="{pigcms{:U('Config/see_tmp_qrcode')}" class="see_qrcode">查看二维码</a></label>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="open_money_tempnews">商家收款微信知</label>
									<select name="open_money_tempnews" id="open_money_tempnews">
										<option value="0" <if condition="$now_merchant.open_money_tempnews eq 0">selected="selected"</if>>关闭</option>
										<option value="1" <if condition="$now_merchant.open_money_tempnews eq 1">selected="selected"</if>>开启</option>
									</select>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										{pigcms{:L('SAVE_BKADMIN')}
									</button>
								</div>
							</div>
						</div>
					</form>
					<!--div style="border:1px solid #c5d0dc;padding-left:22px;margin-bottom:10px;margin-top:20px;">
						<div class="alert alert-info" style="margin-top:10px;">
							<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>首页排序值是用户通过您的商户二维码关注平台公众号来进行获取累加的一种值。该值越高，您的{pigcms{$config.group_alias_name}信息则将有可能在首页展示。<br/>现在系统设置的扫描一次累加的值为（{pigcms{$config.merchant_qrcode_indexsort}）！在首页中被点击一次，将会扣除一点值。<br/>您可以设置将储存自动增长给哪个{pigcms{$config.group_alias_name}，也可以不选择，若不选择团购则会累加值到商家帐号上。
						</div>
						<div class="form-group" style="margin-top:10px;color:red;">您现在的首页排序储存值为 {pigcms{$now_merchant.storage_indexsort}</div>
						<if condition="$merchant_group_list">
							<div class="form-group">
								<label class="col-sm-2">将储存值转给：</label>
								<select name="group_indexsort" id="group_indexsort">
									<option value="0">不转</option>
									<volist name="merchant_group_list" id="vo">
										<option value="{pigcms{$vo.group_id}">[{pigcms{$vo.index_sort}] {pigcms{$vo.s_name}</option>
									</volist>
								</select>
							</div>
							<div class="form-group">
								<label class="col-sm-2">将选中团购设置为自动增长：</label>
								<select name="indexsort_groupid" id="indexsort_groupid">
									<option value="0">不设置</option>
									<volist name="merchant_group_list" id="vo">
										<option value="{pigcms{$vo.group_id}" <if condition="$vo['group_id'] eq $now_merchant['auto_indexsort_groupid']">selected="selected"</if>>[{pigcms{$vo.index_sort}] {pigcms{$vo.s_name}</option>
									</volist>
								</select>
							</div>
							<div class="form-group">
								<button id="indexsort_edit_btn" class="small_btn">修改</button>
							</div>
						<else/>
							<div class="form-group" style="margin-top:10px;color:red;">请您先添加团购信息！</div>
						</if>
					</div-->
				</div>
			</div>
		</div>
	</div>
</div>
<style>
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}

.small_btn{
margin-left: 10px;
padding: 6px 8px;
cursor: pointer;
display: inline-block;
text-align: center;
line-height: 1;
letter-spacing: 2px;
font-family: Tahoma, Arial/9!important;
width: auto;
overflow: visible;
color: #333;
border: solid 1px #999;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
background: #DDD;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#DDDDDD');
background: linear-gradient(top, #FFF, #DDD);
background: -moz-linear-gradient(top, #FFF, #DDD);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#DDD));
text-shadow: 0px 1px 1px rgba(255, 255, 255, 1);
box-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0 -1px 0 rgba(0, 0, 0, .09);
-moz-transition: -moz-box-shadow linear .2s;
-webkit-transition: -webkit-box-shadow linear .2s;
transition: box-shadow linear .2s;
outline: 0;
}
.small_btn:active{
border-color: #1c6a9e;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bbee', endColorstr='#2288cc');
background: linear-gradient(top, #33bbee, #2288cc);
background: -moz-linear-gradient(top, #33bbee, #2288cc);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#33bbee), to(#2288cc));
}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 10){
			alert('最多上传10个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
	
	$('#edit_form').submit(function(){
		$.post("{pigcms{:U('Config/merchant')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Config/merchant')}";
			}else{
				alert(result.info);
			}
		})
		return false;
	});
	
	$('#indexsort_edit_btn').click(function(){
		$(this).prop('disabled',true).html('提交中...');
		$.post("{pigcms{:U('Config/merchant_indexsort')}",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
			alert('处理完成！正在刷新页面。');
			window.location.href = window.location.href;
		});
	});
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Config/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	var loop_time = null, art_index = null;
	$(function(){
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					loop_time = setInterval(is_bind, 1500);
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'绑定管理账号的二维码',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: function(){clearInterval(loop_time);},
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});

		$('#delete_bind').click(function(){
			art.dialog.confirm('你确定要解除绑定的用户吗？', function () {
				$.get("{pigcms{:U('Config/ajax_del_binduser')}", function(response){
					if (response.error_code) {
						art.dialog.tips(response.msg);
					} else {
						$('#has_user').hide();
						$('#no_user').show();
					}
				}, 'json');
			}, function () {});
		});
	});

	function is_bind()
	{
		$.get("{pigcms{:U('Config/has_bind')}", function(response){
			if (response.error_code == false) {
				$('#bind_usernickname').html(response.nickname);
				$('#has_user').show();
				$('#no_user').hide();
				clearInterval(loop_time);
				art.dialog.list['handle'].close();
			}
		}, 'json');
	}
</script>
<include file="Public:footer"/>
