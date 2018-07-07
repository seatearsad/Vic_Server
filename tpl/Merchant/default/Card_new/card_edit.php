<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-credit-card"></i>
                <a href="{pigcms{:U('Card_new/index')}">会员卡</a>
            </li>
        </ul>
    </div>
	<div class="page-content form-horizontal ">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
					<form class="form" method="post" action="" target="_top" enctype="multipart/form-data">
						<div class="tab-content card_new">
							<div class="headings gengduoxian">基本信息<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<div class="form-group">
								<label class="tiplabel"><label>启用会员卡：</label></label>
								<label class="radiolabel first"><span><label><input name="status" value="1" type="radio" <if condition="$card.status eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="status" value="0" type="radio" <if condition="$card.status eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>会员卡背景图：</label></label>
								<select name="bg" id="sys_card_bg" class="pt" style="width:210px;">
									<?php
									for ($i = 4; $i <= 20; $i++) {
										$i = $i < 10 ? '0' . $i : $i;
										$str = './static/images/card/card_bg' . $i . '.png';
										if ($card['bg'] == $str) {
											echo $str = '<option value="' . $str . '" selected="selected" >' . $i . '</option>';
										} else {
											echo $str = '<option value="' . $str . '">' . $i . '</option>';

										}
									}
									?>
								</select>
								<span class="tip">(选择系统内置背景图)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>上传背景图：</label></label>
								<input type="text" name="diybg" id="bgs" class="px" value="{pigcms{$card.diybg}" style="width:210px;"/>
								<a class="fileupload-exists btn btn-ccc" style="margin-left:20px;font-size:12px;" onclick="upyunPicUpload('bgs',1000,600,'card')">上传图片</a>
								<span class="tip">(同时选择系统背景图和上传背景图，优先使用上传背景图)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>卡号文字颜色：</label></label>
								<input type="text" name="numbercolor" id="numbercolor" value="{pigcms{$card.numbercolor}" class="px color" style="width:80px;background-image:none;background-color:rgb(0,0,0);color:rgb(255,255,255);" onblur="document.getElementById('number').style.color=document.getElementById('numbercolor').value;"/>
								<span class="tip"></span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>会员卡折扣数：</label></label>
								<input type="text" name="discount" id="discount" class="px" value="{pigcms{$card.discount}" style="width:210px;"/><span class="tip">(请填写0到10的数字,0相当于不打折,比如95折 填写9.5即可)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>用户自助领卡：</label></label>
								<label class="radiolabel first"><span><label><input name="self_get" value="1" type="radio" <if condition="$card.self_get eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="self_get" value="0" type="radio" <if condition="$card.self_get eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(用户访问会员卡页面时自动领卡)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>支持实体卡：</label></label>
								<label class="radiolabel first"><span><label><input name="is_physical_card" value="1" type="radio" <if condition="$card.is_physical_card eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="is_physical_card" value="0" type="radio" <if condition="$card.is_physical_card eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(用户会员卡页面出现绑定实体卡的选项)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>扫码自动领卡：</label></label>
								<label class="radiolabel first"><span><label><input name="auto_get" value="1" type="radio" <if condition="$card.auto_get eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="auto_get" value="0" type="radio" <if condition="$card.auto_get eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(扫描商家渠道二维码自动领卡)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>消费自动领卡：</label></label>
								<label class="radiolabel first"><span><label><input name="auto_get_buy" value="1" type="radio" <if condition="$card.auto_get_buy eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="auto_get_buy" value="0" type="radio" <if condition="$card.auto_get_buy eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(购买商家商品后自动领卡)</span>
							</div>
							
							
							
							<div class="headings gengduoxian">余额 / 充值<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<if condition="$config.merchant_card_recharge_offline eq 1">
								<div class="form-group">
									<label class="tiplabel"><label>会员卡初始金额：</label></label>
									<input type="text" name="begin_money" id="begin_money" class="px" value="{pigcms{$card.begin_money|floatval=###}" style="width:210px;"/><span class="tip">(领会员卡时自动向该卡赠送金额)</span>
								</div>
							</if>
							<div class="form-group">
								<label class="tiplabel"><label>充值返现规则：</label></label>
								充值&nbsp;&nbsp;<input type="text" name="recharge_count" id="recharge_count" class="px" value="{pigcms{$card.recharge_count}" style="width:60px;"/>&nbsp;&nbsp;元, 返&nbsp;&nbsp;<input type="text" name="recharge_back_money" id="recharge_back_money" class="px" value="{pigcms{$card.recharge_back_money}" style="width:60px;"/>&nbsp;&nbsp;元, 返&nbsp;&nbsp;<input type="text" name="recharge_back_score" id="recharge_back_score" class="px" value="{pigcms{$card.recharge_back_score}" style="width:60px;"/>&nbsp;&nbsp;{pigcms{$config['score_name']}
								<span class="tip">(用户每在线充值一笔达到金额即赠送的金额，可叠加享受。如果设置，请填写大于等于1的整数)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>充值金额建议：</label></label>
								<input type="text" name="recharge_suggest" id="recharge_suggest" class="px" value="{pigcms{$card.recharge_suggest}" style="width:210px;"/><span class="tip">(用户可以在充值页面快速点击按钮充值该建议金额。英文逗号隔开，如 10, 20, 30 )</span>
							</div>
							
							
							
							<div class="headings gengduoxian">{pigcms{$config['score_name']}<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<div class="form-group">
								<label class="tiplabel"><label>会员卡初始{pigcms{$config['score_name']}：</label></label>
								<input type="text" name="begin_score" id="begin_score" class="px" value="{pigcms{$card.begin_score}" style="width:210px;"/><span class="tip">(领会员卡时自动向该卡赠送{pigcms{$config['score_name']})</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>消费获得{pigcms{$config['score_name']}：</label></label>
								<label class="radiolabel first"><span><label><input class="support_score_select" name="support_score_select" value="1" type="radio" <if condition="$card.support_score eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input class="support_score_select" name="support_score_select" value="0" type="radio" <if condition="$card.support_score eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(用户购买商品之后是否能获取一定的{pigcms{$config['score_name']})</span>
							</div>
							<div class="form-group support_score">
								<label class="tiplabel"><label>消费一元获得{pigcms{$config['score_name']}：</label></label>
								<input type="text" name="support_score" id="support_score" class="px" value="{pigcms{$card.support_score}" style="width:210px;"/><span class="tip">(用户每消费一元获得的{pigcms{$config['score_name']}数，大于1的整数)</span>
							</div>
							
							
							
							<div class="headings gengduoxian">优惠券<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<div class="form-group">
								<label class="tiplabel"><label>自动领优惠券：</label></label>
								<label class="radiolabel first"><span><label><input name="auto_get_coupon" value="1" type="radio" <if condition="$card.auto_get_coupon eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="auto_get_coupon" value="0" type="radio" <if condition="$card.auto_get_coupon eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(仅限第一次领卡时触发)</span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>消费自动领优惠券：</label></label>
								<label class="radiolabel first"><span><label><input name="weixin_send" value="1" type="radio" <if condition="$card.weixin_send eq 1">checked="checked"</if> /></label>&nbsp;<span>是</span></span></label>
								<label class="radiolabel"><span><label><input name="weixin_send" value="0" type="radio" <if condition="$card.weixin_send eq 0">checked="checked"</if>/></label>&nbsp;<span>否</span>&nbsp;</span></label>
								<span class="tip">(暂时仅支持微信中购买商家商品自动派发优惠券)</span>
							</div>
							
							
							
							<div class="headings gengduoxian">使用说明<span class="note-inf">带<a style="color:red;">*</a>为必填项</span></div>
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label>充值说明：</label></label>
								<textarea name="recharge_des" id="recharge_des" class="px" style="width:410px;height:120px;">{pigcms{$card.recharge_des}</textarea>
							</div>
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label>{pigcms{$config['score_name']}说明：</label></label>
								<textarea name="score_des" id="score_des" class="px" style="width:410px;height:120px;">{pigcms{$card.score_des}</textarea>
							</div>
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label>会员卡说明：</label></label>
								<textarea name="info" id="info" class="px" style="width:410px;height:120px;">{pigcms{$card.info}</textarea>
							</div>
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="vipcard_box">
		<div class="vipcard">
			<img id="cardbg" src="<if condition="$card.diybg neq ''">{pigcms{$card.diybg}<else />{pigcms{$card.bg}</if>"/>
			<strong class="pdo verify" id="number" style="color:{pigcms{$card.numbercolor}"><span>6537 1998</span></strong>
		</div>
		<span class="red">背景图宽540px高320px，图片类型png。</span>
	</div>
</div>

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script src="./static/js/cart/jscolor.js" type="text/javascript"></script>
<link rel="stylesheet" href="./static/kindeditor/themes/default/default.css"/>
<link rel="stylesheet" href="./static/kindeditor/plugins/code/prettify.css"/>
<script src="./static/kindeditor/kindeditor.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
		$('#sys_card_bg').change(function(){
			if($.trim($('#bgs').val()) == ''){
				$('#cardbg').attr('src', $(this).val());
			}
		});
		
		if($('.support_score_select:checked').val()==0){
            $('.support_score').css('display','none');
		}else{
            $('.support_score').css('display','block');
		}

       $('#support_recharge').change(function(event) {
           if($('#support_recharge').val()==0){
                $('.support_recharge').css('display','none');

           }else{
                $('.support_recharge').css('display','block');
           }
       });

       $('.support_score_select').change(function(event) {
            if($('.support_score_select:checked').val()==0){
                $('.support_score').css('display','none');
           }else{
                $('.support_score').css('display','block');
           }
       });
    });
	function upload_func(){
		$('#cardbg').attr('src',$('#bgs').val());
	}
</script>
<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>
<include file="Public:footer"/>