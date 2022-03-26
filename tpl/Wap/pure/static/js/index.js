var myScroll,wx;
$(function(){
	/*if(!app_version){
	$(window).resize(function(){
		window.location.reload();
});}*/
	if($('.activity').size() > 0){
		var timeDDom = $('.time_d:eq(0)');
		var timeHDom = $('.time_h:eq(0)');
		var timeMDom = $('.time_m:eq(0)');
		var timeSDom = $('.time_s:eq(0)');
		var timer = setInterval(function(){
			var timeJ = parseInt(timeDDom.html());
			var timeH = parseInt(timeHDom.html());
			var timeM = parseInt(timeMDom.html());
			var timeS = parseInt(timeSDom.html());
			if(timeS == 0){
				if(timeM == 0){
					if(timeH == 0){
						if(timeJ == 0){
							clearInterval(timer);
							window.location.reload();
						}else{
							$('.time_d').html(format_time(timeJ-1));
						}
						$('.time_h').html('23');
					}else{
						$('.time_h').html(format_time(timeH-1));
					}
					$('.time_m').html('59');
				}else{
					$('.time_m').html(format_time(timeM-1));
				}
				$('.time_s').html('59');
			}else{
				$('.time_s').html(format_time(timeS-1));
			}
		},1000);
	}
	var upIcon = $("#up-icon"),
		downIcon = $("#pullDown");
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:false,useTransform:false,useTransition:false});
	//myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false});
	// myScroll.on("scroll",function(){
	// 	if(this.y >= 60){
	// 		if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('释放可以刷新');
	// 		return "";
	// 	}else if(this.y < 60 && this.y > 0){
	// 		if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
	// 		return "";
	// 	}
    //
	// 	/*if(maxY >= 50){
	// 		!upHasClass && upIcon.addClass("reverse_icon");
	// 		return "";
	// 	}else if(maxY < 50 && maxY >=0){
	// 		upHasClass && upIcon.removeClass("reverse_icon");
	// 		return "";
	// 	}*/
	// });
//http://www.zhangyunling.com/study/slideUpDownRefresh/version_1/iscroll-test.html
// 	myScroll.on("slideDown",function(){
// 		if(this.y > 60){
// 			//alert("slideDown");
// 			$('#container').css({'bottom':0});
// 			$('.footerMenu,#pullDown').hide();
// 			$('#scroller').animate({'top':$(window).height()+'px'},function(){
// 				upIcon.removeClass("reverse_icon");
// 				pageLoadTip();
// 				window.addEventListener("pagehide", function(){
// 					$('#container').css({'bottom':'49px'});
// 					$('#scroller').css({'top':'0px'});
// 					$('.footerMenu,#pullDown').show();
// 					pageLoadTipHide();
// 				},false);
// 				window.location.href =window.location.href;
// 			});
// 		}
// 	});

	/*myScroll.on("slideUp",function(){
		if(this.maxScrollY - this.y > 50){
			alert("slideUp");
			upIcon.removeClass("reverse_icon")
		}
	});*/

	var mySwiper = $('.swiper-container1').swiper({
		pagination:'.swiper-pagination1',
		loop:true,
		grabCursor: true,
		paginationClickable: true,
		autoplay:3000,
		autoplayDisableOnInteraction:false,
		simulateTouch:false
	});
	var mySwiper2 = $('.swiper-container2').swiper({
		pagination:'.swiper-pagination2',
		loop:true,
		grabCursor: true,
		paginationClickable: true,
		simulateTouch:false
	});
	$('.swiper-container3 .swiper-slide').width($('.swiper-container3 .swiper-slide').width());
	var mySwiper3 = $('.swiper-container3').swiper({
		freeMode:true,
		freeModeFluid:true,
		slidesPerView: 'auto',
		simulateTouch:false/*,
		centeredSlides: true*/
	});
	$('.swiper-container4 .swiper-slide').width($('.swiper-container4 .swiper-slide').width());
	var mySwiper4 = $('.swiper-container4').swiper({
		freeMode:true,
		freeModeFluid:true,
		slidesPerView: 'auto',
		simulateTouch:false/*,
		centeredSlides: true*/
	});

    //motify.log('正在加载333内容',0,{show:true});
	if(user_long == '0'){
		getUserLocation({errorAction:1,okFunction:'getRecommendList',errorFunction:'getRecommendList'});
	}else{
		getRecommendList();
	}
	if($('.platformNews').size() > 0){
		$('.platformNews .list').width($(window).width()-20-73);
		var platformNewsIndex = 0;
		var platformNewsSize = $('.platformNews .list li').size();
		setInterval(function(){
			platformNewsIndex += 1;
			if((platformNewsIndex*2)+2>platformNewsSize){
				platformNewsIndex = 0;
			}
			$('.platformNews .list li').hide();
			$('.platformNews .list').find('.num-'+((platformNewsIndex*2)+1)+',.num-'+((platformNewsIndex*2)+2)).show();
		},4000);
	}

	$('#qrcodeBtn').click(function(){
		if(motify.checkWeixin()){
			motify.log('正在调用二维码功能');
			wx.scanQRCode({
				desc:'scanQRCode desc',
				needResult:0,
				scanType:["qrCode"],
				success:function (res){
					// alert(res);
				},
				error:function(res){
					motify.log('微信返回错误！请稍后重试。',5);
				},
				fail:function(res){
					motify.log('无法调用二维码功能');
				}
			});
		}else{
			motify.log('您不是微信访问，无法使用二维码功能');
		}
	});
	
	$('#moress').click(function(){
		getRecommendList();
	})

	$(document).on('click','.recommend-link-url',function(){
		pageLoadTip({showBg:false});
		var tmpObj = $(this);
		var id = tmpObj.data('group_id');
		//$.post(group_index_sort_url,{id:id},function(){
			redirect(tmpObj.data('url'),tmpObj.data('url-type'));
			return false;
		//});
	});
});
var like_page	=	1;
var page_count	=	10;
var has_more = true;
function getRecommendList(){
	//alert("getRecommendList");
	pageLoadTip({showBg: false});
	has_more = false;
	$.post(window.location.pathname + '?c=Groupservice&a=indexRecommendList&page=' + like_page + '&long=' + $.cookie('userLocationLong') + '&lat=' + $.cookie('userLocationLat') + '&sort=' + sortType, function (result) {
		if (guess_content_type == 'group' || guess_content_type == 'shop') {
			if (result.length < page_count) {
				//$("#moress").remove();
			}
		} else if (guess_content_type == 'meal') {
			if (result.store_list.length < page_count) {
				//$("#moress").remove();
			}
		}

		if (result != '') {
			if (like_page == 1) $('.youlike').show().find('.likeBox').empty();
			laytpl($('#indexRecommendBoxTpl').html()).render(result.store, function (html) {
				$('.youlike').show().find('.likeBox').append(html);
			});

            if (like_page == 1) {
                var html = '';
                for (var i = 0; i < result.sub_nav.length; ++i) {
                    var nav = result.sub_nav[i];
                    if(nav.image == ""){
                    	nav.image =static_url+"images/icon_category_default.png";
					}
                    html += "<a href='" + window.location.pathname + "?c=Shop&a=index&cat=" + nav.fid + '-' + nav.id + "'>" +
                        "<li>" +
                        "<div>" +
                        "<img src='" + nav.image + "' />" +
                        "</div>" +
                        "<div>" + nav.title + "</div>" +
                        "</li>"
                }

                $('#category ul').html(html);

                laytpl($('#indexRecommendListTpl').html()).render(result.recommend, function (html) {
                    $('#recommendList').html(html);
                });

                if(result.system_message != null){
                	var system_message = result.system_message;
					$('body').css("overflow","hidden");
                    if(system_message['type'] == 1){
                        $('#system_message').show();
                        $('#system_message').bind("click",function () {
                            $(this).hide();
                            $('#message_content').html("");
                            $('body').css("overflow","auto");
                        });

						var img_width = $('#message_content').width();
						var img = "<img src='"+system_message['content']+"' width='"+img_width+"'/>";
                        img += "<img src='/tpl/Static/blue/images/new/icon-close-white.png' id='close_message' width='50' style='display: block;margin: 10px auto;'/>";
						$('#message_content').html(img);

						$('#close_message').bind("click",function () {
                            $('#system_message').hide();
                            $('#message_content').html("");
                            $('body').css("overflow","auto");
                        });

                        $('#message_content').css('left',(window_width - img_width)/2);
                        $('#message_content').css('top',(window_height - img_width*1.25)/2);

					}else{
                        var messageLayer = layer.open({
                            content: system_message['content'],
                            btn: ['Confirm'],
                            end: function () {
                                layer.close(messageLayer);
                            }
                        });
					}
				}
            }
		}

		//like_page	=	like_page+page_count;
		if (like_page >= guess_num) {
			//$("#moress").remove();
		}
        
		pageLoadTipHide();
        if(result.has_more){
            like_page++;
            has_more = result.has_more;
        }else{
            $("#moress").remove();
		}
		//myScroll.refresh();
	},"json");
}
function format_time(time){
	return time < 10 ? '0'+time : time;
}