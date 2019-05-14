$(function(){
	if($('#account').val() == ''){
		$('#account').focus();
	}else if($('#pwd').val() == ''){
		$('#pwd').focus();
	}else{
		$('#verify').focus();
	}
	
	$('#form').submit(function(){
		notice('Login...','loading');
		if($('#account').val()==''){
			notice('Invalid Username!','error');
			$('#account').focus();
		}else if($('#pwd').val()==''){
			notice('Invalid Password!','error');
			$('#pwd').focus();
		}else if($('#verify').val().length!=4){
			notice('Invalid Verification Code!','error');
			$('#verify').focus();
		}else{
			$.post(login_check,$("#form").serialize(),function(data){
				var msg;
				var pic;
				if(data==1){
					msg = 'Success!';
					pic = 'ok';
					setTimeout(function(){
						window.parent.location = system_index;
					},1000);
				}else{
					pic = 'error';
					// if(data==-1){$('#verify').focus();msg='验证码不正确！';}
					// else if(data==-2){$('#account').focus();msg='用户名不存在！';}
					// else if(data==-3){$('#pwd').focus();msg='密码错误！';}
					// else if(data==-4){$('#account').focus();msg='用户被禁止登录！';}
					// else if(data==-5){$('#account').focus();msg='登录信息保存失败,请重新登录！';}
					// else{msg='登录出现异常，请重试！';}
                    if(data==-1){$('#verify').focus();msg='Invalid Verification Code!';}
                    else if(data==-2){$('#account').focus();msg='Invalid Username!';}
                    else if(data==-3){$('#pwd').focus();msg='Invalid Password!';}
                    else if(data==-4){$('#account').focus();msg='用户被禁止登录！';}
                    else if(data==-5){$('#account').focus();msg='登录信息保存失败,请重新登录！';}
                    else{msg='登录出现异常，请重试！';}
				}
				notice(msg,pic);
			});
		}
		return false;
	});
});
function fleshVerify(url){
	var time = new Date().getTime();
	$('#verifyImg').attr('src',url+"&time="+time);
}
function notice(msg,pic){
	$('.notice').remove();
	$('body').append('<div class="notice"><img src="'+static_path+'login/img/'+pic+'.gif" />'+msg+'</div>');
	setTimeout(function(){
		$('.notice').remove();
	},3000);
}