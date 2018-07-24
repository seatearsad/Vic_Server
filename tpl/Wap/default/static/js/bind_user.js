$(function(){
	$('#reg-form').submit(function(){
		var phone = $.trim($('#reg_phone').val());
		$('#reg_phone').val(phone);
		if(phone.length == 0){
			$('#tips').html(getLangStr('_B_LOGIN_ENTERPHONENO_')).show();
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

		if(typeof(password)!='undefined'&&password.length < 6){
			$('#tips').html(getLangStr('_B_D_LOGIN_6KEYWORD_')).show();
			return false;
		}
		
		if(typeof(sms_code)!='undefined'){
			if(sms_code.length > 6||isNaN(sms_code)){
				$('#tips').html('输入的短信验证码有误。').show();
				return false;
			}
		}
		
		$.post($('#reg-form').attr('action'),{phone:phone,password:password,sms_code:sms_code,bind_exist:0},function(result){
			if(result.status == '1'){
				window.location.href = $('#reg-form').attr('location_url');
			}else{
				if(result.info=='phone_exist'){
					$('#tips').html("pigcms{:L('_B_LOGIN_PHONENOHAVE_')").show();
					if(confirm("{pigcms{:L('_IS_CONFIRM_BIND_')}")){
						$.post($('#reg-form').attr('action'),{phone:phone,password:password,sms_code:sms_code,bind_exist:1},function(res){
							$('#tips').html(res.info).show();
							if(res.status=='1'){
								window.location.reload();
							}
						});
					}
				}else{
					$('#tips').html(result.info).show();
				}
			}
		});
		return false;
	});
	
	$('#reg_changeWord').click(function(){
		if($(this).html() == getLangStr('_B_D_LOGIN_DISPLAY_')){
			$('#reg_txt_password').val($('#reg_pwd_password').val()).show();
			$('#reg_pwd_password').hide();
			$(this).html(getLangStr('_B_D_LOGIN_DISPLAY_PASS_'));
			$('#reg_password_type').val(1);
		}else{
			$('#reg_pwd_password').val($('#reg_txt_password').val()).show();
			$('#reg_txt_password').hide();
			$(this).html(getLangStr('_B_D_LOGIN_DISPLAY_'));
			$('#reg_password_type').val(0);
		}
	});
});