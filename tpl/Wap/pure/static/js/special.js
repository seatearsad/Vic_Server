var nowIndex = 0;
$(function(){
	if($('.coupon_list').data('coupon') != ""){
		$.post(getCouponUrl,{ids:$('.coupon_list').data('coupon')},function(result){
			if(result.status == 1){
				laytpl($('#couponTpl').html()).render(result.info, function(html){
					$('.coupon_list').append(html);
					$('.couponRow').css({'margin-top':$('.coupon_list').width()*0.02+'px','height':(129*$('.couponRow').width()/222)+'px'});
					$('.addCategoryBtn').css('margin-top',$('.coupon_list').width()*0.02+'px');
					// $('.coupon_name').css({'margin-left':($('.couponRow').width()*56/222-14)/2,'margin-right':$('.couponRow').width()/10});
					$('.coupon_name').css({'margin-left':($('.couponRow').width()*56/222-14)/2});
					$('.coupon_use,.coupon_monery').css({'width':$('.couponRow').width()*134/222,'margin-left':$('.couponRow').width()*18/222});
					$('.coupon_monery').css({'margin-top':$('.couponRow').height()*30/129});
					$('.coupon_use').css({'margin-top':$('.couponRow').height()*26/129*2-18});
					
					// $('.coupon_list').css({'margin-top':'-'+($('.couponRow:eq(0)').height() + ($('.couponRow:eq(0)').height())*0.2)+'px'});
				});
				
				$('.couponRow').click(function(){
					if($(this).hasClass('coupon_on1')){
						return false;
					}
					var couponDom = $(this);
					if(hasLogin == false){
						layer.open({
							title:['登录提示','background-color:#FF658E;color:#fff;'],
							content:'您需要先登录才能领取优惠券，是否前往登录？',
							btn: ['确定','取消'],
							yes:function(){
								window.location.href = LoginUrl;
							}
						});
						return false;
					}
					if(userPhone == ''){
						layer.open({
							title:['绑定手机','background-color:#FF658E;color:#fff;'],
							content:'您需要先绑定手机号码才能继续领取优惠券，是否前往绑定？',
							btn: ['确定','取消'],
							yes:function(){
								window.location.href = BindPhoneUrl;
							}
						});
						return false;
					}
					layer.open({type: 2});
					$.post(receiveCouponUrl,{coupon_id:couponDom.data('id'),phone:userPhone},function(data, textStatus, xhr) {
						layer.closeAll();
						switch(data.error_code){	
							case 0:
								motify.log("领取优惠券成功");
								break;
							case 1:
								motify.log("领取优惠券发生错误");
								break;
							case 2:
								couponDom.addClass('coupon_on1');
								motify.log("该优惠券已过期");
								break;
							case 3:
								couponDom.addClass('coupon_on1');
								motify.log("该优惠券已被领完");
								break;
							case 4:
								couponDom.addClass('coupon_on1');
								motify.log("该优惠券只允许新用户领取");
								break;
							case 5:
								couponDom.addClass('coupon_on1');
								motify.log("您已经领取过了");
								break;
						}
					},"json");
				});
			}
		});
	}
		

	$('.addCategoryBtn li').click(function(){
		if($(this).hasClass('curr')){
			return false;
		}
		$(this).addClass('curr').siblings().removeClass('curr');
		nowIndex = $(this).index();
		if($('.productRow.cat-'+nowIndex).size() > 0){
			$('.productRow').hide();
			$('.productRow.cat-'+nowIndex).show();
			return false;
		}
		
		motify.log("加载店铺中...",0,{show:true});
		$.post(getShopUrl,{user_lat:user_lat,user_long:user_long,ids:$(this).data('product')},function(result){
			$('.productRow').hide();
			motify.clearLog();
			if(result.status == 1){
				laytpl($('#productTpl').html()).render(result.info, function(html){
					$('.productBox').append(html);
					$('.productRow.cat-'+nowIndex+':last').css('margin-bottom',0);
					if(!motify.checkMobile()){
						$('.productRow').removeClass('link-url');
					}
				});
			}
		});
	});
	$('.addCategoryBtn li:eq(0)').trigger('click');
	
	$(document).on('click','.hasMore',function(){
		$(this).toggleClass('showMore');
		return false;
	});
	
	if(!motify.checkMobile()){
		$(document).on('click','.productRow',function(){
			motify.log('请使用手机访问！');
			return false;
		});
	}
	if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50 && motify.checkIos()){
		$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
	}
});


function parseCoupon(obj,type){
	var returnObj = {};
	for(var i in obj){
		if(typeof(obj[i]) == 'object'){
			returnObj[i] = [];
			for(var j in obj[i]){
				returnObj[i].push('满'+obj[i][j].money+'元减'+obj[i][j].minus+'元');
			}
		}else if(i=='invoice'){
			returnObj[i] = '满'+obj[i]+'元支持开发票，请在下单时填写发票抬头';
		}else if(i=='discount'){
			returnObj[i] = '店内全场'+obj[i]+'折';
		}
	}
	var textObj = [];
	for(var i in returnObj){
		if(typeof(returnObj[i]) == 'object'){
			switch(i){
				case 'system_newuser':
					textObj[i] = '平台首单'+returnObj[i].join(',');
					break;
				case 'system_minus':
					textObj[i] = '平台优惠'+returnObj[i].join(',');
					break;
				case 'newuser':
					textObj[i] = '店铺首单'+returnObj[i].join(',');
					break;
				case 'minus':
					textObj[i] = '店铺优惠'+returnObj[i].join(',');
					break;
				case 'system_minus':
					textObj[i] = '平台优惠'+returnObj[i].join(',');
					break;
				case 'delivery':
					textObj[i] = '配送费'+returnObj[i].join(',');
					break;
			}
		}else if(i=='invoice' || i=='discount'){
			textObj[i] = returnObj[i];
		}
	}
	if(type == 'text'){
		var tmpObj = [];
		for(var i in textObj){
			tmpObj.push(textObj[i]);
		}
		return tmpObj.join(';');
	}else{
		return textObj;
	}
}