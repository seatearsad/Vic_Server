var layer_index = null;
$(function(){
	if($('.leftMenu').size() > 0){
		$('.leftMenu').height($(window).height()-50);
		$('.rightMain').height($(window).height()-60);
	}
	$('.urlLink').click(function(){
		var url = $(this).data('url');
		if(url == 'reload'){
			location.reload();
		}else{
			location.href = url;
		}
		
	});
	
	$(window).resize(function(){
		location.reload();
	});
	
	if($('.fixed_header').size() > 0){
		var fhh = $('.fixed_header').height()+20;
		var fht = $('.fixed_header').offset().top
		$('.rightMain').css('padding-top',fhh);
		$('.fixed_header').css({'position':'fixed','top':fht-10,'width':$('.rightMain').width()});
		
		$('.rightMain').height($(window).height()-60-fhh);
	}
	
	$('.handle_btn').live('click',function(){
		var areaWH = ['80%', '80%'];
		if($(this).data('box_width')){
			areaWH[0] = $(this).data('box_width');
		}
		if($(this).data('box_height')){
			areaWH[1] = $(this).data('box_height');
		}
		layer_index = layer.open({
			id: $(this).data('layer_id') ? $(this).data('layer_id') : '',
			type: 2,
			title: $(this).data('title') ? ($(this).data('title') != 'no' ? $(this).data('title') : false) : '按钮缺少 data-title 参数',
			shadeClose: true,
			shade: 0.6,
			area: areaWH,
			content: $(this).attr('href')
		});
		return false;
	});
});