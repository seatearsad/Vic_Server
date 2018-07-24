$(function(){
	$('#login-form').submit(function(){
		var phone = $.trim($('#phone').val());
		$('#phone').val(phone);
		if(phone.length == 0){
			$('#tips').html(getLangStr('_B_LOGIN_ENTERPHONENO_')).show();
			return false;
		}
		//if(!/^[0-9]{10}$/.test(phone)){
		//	$('#tips').html('请输入10位数字的手机号码。').show();
		//	return false;
		//}
		
		var password = $('#password').val();
		if(password.length == 0){
			$('#tips').html(getLangStr('_B_LOGIN_ENTERKEY_')).show();
			return false;
		}
		
		$.post($('#login-form').attr('action'),{phone:phone,password:password},function(result){
			if(result.status == '1'){
				window.location.href = $('#login-form').attr('location_url');
			}else{
				$('#tips').html(result.info).show();
				$('#forgetpwd').show();
			}
		});
		
		return false;
	});
});