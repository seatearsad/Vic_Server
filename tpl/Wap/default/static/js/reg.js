var reg_flag = true;
$(function(){
	$('#reg-form').submit(function(){
		var phone = $.trim($('#phone').val());
		$('#phone').val(phone);
		if(phone.length == 0){
			$('#tips').html('请输入手机号码。').show();
			return false;
		}
		//if(!/^[0-9]{10}$/.test(phone)){
		//	$('#tips').html('请输入10位数字的手机号码。').show();
		//	return false;
		//}
		
		var password_type = $('#password_type').val();
		if($('#sms_code')&&$('#sms_code').val()==''){
			$('#tips').html('输入的短信验证码有误。').show();
			return false;
		}
		var sms_code = $('#sms_code').val();
		if(password_type === '0'){
			var password = $('#pwd_password').val();
		}else{
			var password = $('#txt_password').val();
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
		if(reg_flag){
			reg_flag = false;
		}else{
			$('#tips').html('注册中，请不要重复提交').show();
			return false;
		}
		$.post($('#reg-form').attr('action'),{phone:phone,password:password,sms_code:sms_code},function(result){
			if(result.status == '1'){
				window.location.href = $('#reg-form').attr('location_url');
			}else{
				reg_flag = true;
				$('#tips').html(result.info).show();
			}
		});
		
		return false;
	});
	
	
	$('#changeWord').click(function(){
		if($(this).html() == '显示明文'){
			$('#txt_password').val($('#pwd_password').val()).show();
			$('#pwd_password').hide();
			$(this).html('显示密文');
			$('#password_type').val(1);
		}else{
			$('#pwd_password').val($('#txt_password').val()).show();
			$('#txt_password').hide();
			$(this).html('显示明文');
			$('#password_type').val(0);
		}
	});
});