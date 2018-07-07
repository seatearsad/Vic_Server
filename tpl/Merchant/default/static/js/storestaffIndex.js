$(function(){
	$('.pageBg').css('height',$(window).height()*0.35);
	$('.infoBox').css('top',$(window).height()*0.35-90);
	
	$('.pageLink li').click(function(){
		if($(this).data('confirm')){
			if(confirm($(this).data('confirm'))){
				location.href = $(this).data('url');
			}
		}else{
			location.href = $(this).data('url');
		}
	});
	
	$(window).resize(function(){
		location.reload();
	});
});