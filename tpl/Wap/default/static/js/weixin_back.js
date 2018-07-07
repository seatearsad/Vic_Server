var reg_flag = true;
$(function(){
	$('.taba .slide').css({'left':$('.taba .active').offset().left,'width':$('.taba .active').width()});
	$(window).resize(function(){
		$('.taba .slide').css({'left':$('.taba .active').offset().left,'width':$('.taba .active').width()});
	});
	
	$('#login-form').submit(function(){
		var phone = $.trim($('#login_phone').val());
		$('#login_phone').val(phone);
		if(phone.length == 0){
			$('#tips').html('请输入手机号码。').show();
			return false;
		}
		//if(!/^[0-9]{10}$/.test(phone)){
		//	$('#tips').html('请输入10位数字的手机号码。').show();
		//	return false;
		//}
		
		var password = $('#login_password').val();
		if(password.length == 0){
			$('#tips').html('请输入密码。').show();
			return false;
		}
		
		$.post($('#login-form').attr('action'),{phone:phone,password:password},function(result){
			if(result.status == '1'){
				if(is_specificfield){
					layer.open({
						title:['提醒：','background-color:#8DCE16;color:#fff;'],
						content:'请填写个人完善信息，能更快通过审核认证，获得优惠哦！',
						btn: ['确认', '取消'],
						shadeClose: false,
						yes: function(){
							window.location.href = "{pigcms{:U('My/inputinfo')}";
						},
						no: function(){
							window.location.href = $('#login-form').attr('location_url');
						}
					});
				}else{
					window.location.href = $('#login-form').attr('location_url');
				}
			}else{
				$('#tips').html(result.info).show();
			}
		});
		
		return false;
	});
	
	$('.taban li').click(function(){
		$(this).addClass('active').siblings('li').removeClass('active');
		$('#'+$(this).attr('tab-target')).show().siblings('form').hide();
		
		$('.taba .slide').css({'left':$('.taba .active').offset().left,'width':$('.taba .active').width()});
	});
	
	$('#reg-form').submit(function(){
		if(reg_flag){
			reg_flag = false;
		}else{
			$('#tips').html('注册中，请不要重复提交').show();
			return false;
		}
		var openid = $('#openid').val();
		var phone = $.trim($('#reg_phone').val());
		$('#reg_phone').val(phone);
		if(phone.length == 0){
			$('#tips').html('请输入手机号码。').show();
			return false;
		}
		//if(!/^[0-9]{10}$/.test(phone)){
		//	$('#tips').html('请输入10位数字的手机号码。').show();
		//	return false;
		//}
		
		var password_type = $('#reg_password_type').val();
		if($('#sms_code')&&$('#sms_code').val()==''){
			$('#tips').html('输入的短信验证码有误。').show();
			return false;
		}
		
		var sms_code = $('#sms_code').val();
		if(password_type === '0'){
			var password = $('#reg_pwd_password').val();
		}else{
			var password = $('#reg_txt_password').val();
		}
		if(password.length < 6){
			$('#tips').html('请输入6位以上的密码。').show();
			return false;
		}
		
		if(typeof(sms_code)!='undefined'){
			if(sms_code.length > 6||isNaN(sms_code)){
				$('#tips').html('输入的短信验证码有误。').show();
				return false;
			}
		}
		$.post($('#reg-form').attr('action'),{phone:phone,password:password,sms_code:sms_code,openid:openid},function(result){
			if(result.status == '1'){
				if(is_specificfield){
					layer.open({
						title:['提醒：','background-color:#8DCE16;color:#fff;'],
						content:'请填写个人完善信息，能更快通过审核认证，获得优惠哦！',
						btn: ['确认', '取消'],
						shadeClose: false,
						yes: function(){
							window.location.href = "{pigcms{:U('My/inputinfo')}";
						},
						no: function(){
							window.location.href = $('#reg-form').attr('location_url');
						}
					});
				}else{
					window.location.href = $('#reg-form').attr('location_url');
				}
			}else{
				if(result.info == '-1'){
					alert('您的微信号已经注册，正在跳转');
					window.location.href = $('#reg-form').attr('location_url');
				}else{
					$('#tips').html(result.info).show();
				}
			}
		});
		return false;
	});
	
	
	$('#reg_changeWord').click(function(){
		if($(this).html() == '显示明文'){
			$('#reg_txt_password').val($('#reg_pwd_password').val()).show();
			$('#reg_pwd_password').hide();
			$(this).html('显示密文');
			$('#reg_password_type').val(1);
		}else{
			$('#reg_pwd_password').val($('#reg_txt_password').val()).show();
			$('#reg_txt_password').hide();
			$(this).html('显示明文');
			$('#reg_password_type').val(0);
		}
	});
});