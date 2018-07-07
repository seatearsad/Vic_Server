var goodsCart = [], goodsNumber = 0, goodsCartMoney = 0,goodsExtraPrice=0; 
jQuery.cookie = function (key, value, options) {
    if (arguments.length > 1 && (value === null || typeof value !== "object")){
        options = jQuery.extend({}, options);
        if (value === null) {
            options.expires = -1;
        }
        if (typeof options.expires === 'number'){
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }
        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '',
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
/* 简单的消息弹出层 */
var motify = {
	timer:null,
	/*shade 为 object调用 show为true显示 opcity 透明度*/
	log:function(msg,time,shade){
		$('.motifyShade,.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		if(shade && shade.show){
			if($('.motifyShade').size() > 0){
				$('.motifyShade').css({'background-color':'rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+')'}).show();
			}else{
				$('body').append('<div class="motifyShade" style="display:block;background-color:rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+');"></div>');
			}
		}
		if(typeof(time) == 'undefined'){
			time = 3000;
		}
		if(time != 0){
			motify.timer = setTimeout(function(){
				$('.motify').hide();
			},time);
		}
	},
	clearLog:function(){
		$('.motifyShade,.motify').hide();
	}
};

$(function() {
	//设置遮罩层的高度
	$(".Mask").css("height", $(document).height());
	
	//搜素
	$(".foodleft .search").click(function(){
		$(this).addClass("foodleftOn")
		var w = $(window).width();
		$(".foodleftOn").css("width", w - 3);
		$(".foodleftOn input").css("width", w - 100);
		$('.sr').focus();
	});

	$(".foodnav li a").click(function(){
		if($(".search").hasClass("foodleftOn")){
			$(".search").removeClass("foodleftOn");
		}
	});

	//背景单窗高度  
	var hi = $(window).height()
//	$(".foodnav").css("height", hi - 104);
	$(".foodnav").css("height", hi - 50);
	$(".foodright").css("height", hi - 50);
	$(".foodright dl").last().css("min-height", hi-50);
	
	/*左侧滚动条*/
	var myScroll2 = new IScroll('.foodnav', {click: true});
	$(".foodright").scroll(function(){
		var top = $(".foodright").scrollTop();
		var menu = $(".foodnav");
		var item = $(".foodright dl");
		var onid = "";
		item.each(function() {
			var n = $(this);
			var itemtop = $('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop();
			if (top > itemtop - 100) {
				onid = n.data('cat_id');
			}
		});
		var link = menu.find(".on");
		link.removeClass("on");
		menu.find("[data-cat_id="+onid+"]").addClass("on");
	});
	$(document).on('click','.foodnav a',function(){
//		$(".foodnav").find('li a').removeClass("on");
//		$(this).addClass("on");
		$('.foodright').animate({scrollTop:$('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop()},500) ;
	});
	
	

	$(document).on('click', '.Addsub a', function(){
		var this_num = $(this).siblings("input").val(), name = $(this).data('name'), price = parseFloat($(this).data('price')), goods_id = parseInt($(this).data('id')), goodsCartKey = $(this).data('index'),goods_extra_price = parseFloat($(this).data('extra_pay_price')),goods_extra_price_name = $(this).data('extra_price_name');
		
		if ($(this).attr('class') == 'jia') {
			this_num ++;
			goodsNumber ++;
			goodsCartMoney += price;
			if(goods_extra_price>0){
				goodsExtraPrice +=goods_extra_price;
			}
		} else {
			this_num --;
			goodsNumber --;
			goodsCartMoney -= price;
			if(goods_extra_price>0){
				goodsExtraPrice-=goods_extra_price;
			}
		}
	
		goodsCartMoney = parseFloat(goodsCartMoney.toFixed(2));
		
		var this_index = null;
//		for (var i in goodsCart) {
//			if (goodsCart[i].goodsCartKey == goodsCartKey) {
//				this_index = i;
//			}
//		}
		
		for (var i in goodsCart) {
			var old_goodsCartKey = goodsCart[i].goods_id;
			if (goodsCart[i]['params'].length) {
				for (var pi in goodsCart[i]['params']) {
					if (goodsCart[i]['params'][pi].type == 'spec') {
						old_goodsCartKey += '_s_' + goodsCart[i]['params'][pi].id;
					}
					if (goodsCart[i]['params'][pi]['data'].length) {
						for (var di in goodsCart[i]['params'][pi]['data']) {
							old_goodsCartKey += '_v_' + goodsCart[i]['params'][pi]['data'][di].id;
						}
					}
				}
			}
			if (goodsCartKey == old_goodsCartKey) {
				this_index = i;
				break;
			}
		}
		
		if (this_index != null) {
			goodsCart[this_index].num = this_num;
		} else {
			if(goods_extra_price>0){
				
				goodsCart.push({
					'goods_id':goods_id,
					'num':this_num,
					'name':name,
					'price':price,
					'extra_price':goods_extra_price,
					'extra_price_name':goods_extra_price_name,
					'params':''});
			}else{
				
				goodsCart.push({
					'goods_id':goods_id,
					'num':this_num,
					'name':name,
					'price':price,
					'params':''});
			}
		}
		
		$('.goods_' + goodsCartKey).find("input").val(this_num);
		if (this_num > 0) {
			$(this).siblings().show();
			if (!$('.Cart_list ul').find('.goods_' + goodsCartKey).length) {
				if(goods_extra_price>0){
					price +='+'+goods_extra_price+goods_extra_price_name;
				}
				var cart_goods_html = '';
				cart_goods_html += '<li class="clr goods_' + goodsCartKey + '">';
				cart_goods_html += '<div class="Clist_left">';
				cart_goods_html += '<h2>' + name + '</h2>';
//				cart_goods_html += '<span>(大份、微辣)</span>';
				cart_goods_html += '</div>';
				cart_goods_html += '<div class="Clist_right">';
				cart_goods_html += '<div class="MenuPrice"><i>$</i>' + price + '</div>';
				cart_goods_html += '<div class="Addsub">';
				cart_goods_html += '<a href="javascript:void(0)" class="jian" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"'+ 'data-extra_pay_price="' + goods_extra_price +'" data-extra_price_name="' + goods_extra_price_name + '"></a>';
				cart_goods_html += '<input type="text" value="' + this_num + '" readOnly="true" class="num">';
				cart_goods_html += '<a href="javascript:void(0)" class="jia" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"'+ 'data-extra_pay_price="' + goods_extra_price +'" data-extra_price_name="' + goods_extra_price_name + '"></a>';
				cart_goods_html += '</div>';
				cart_goods_html += '</div>';
				cart_goods_html += '</li>';
				$('.Cart_list ul').append(cart_goods_html);
			}
		} else {
			$('.goods_' + goodsCartKey).find('.jia').siblings().hide();
			$('.Cart_list ul').find('.goods_' + goodsCartKey).remove();
			if (!$('.Cart_list ul').find('li').length) {
				$(".Cart").slideUp();
				$(".Mask").hide();
			}
		}

		if (goodsNumber > 0) {
			$(".floor").addClass("floorOn");
			$(".qty").show(500).text(goodsNumber);
			if(goodsExtraPrice>0){
				$('#total_price').text(goodsCartMoney+'+'+goodsExtraPrice+goods_extra_price_name);
			}else{
				$('#total_price').text(goodsCartMoney);
			}
			
		} else {
			goodsCart = [];
			$(".floor").removeClass("floorOn");
			$(".qty").hide(500);
			$('#total_price').text(0);
		}
		stringifyCart();
	});
	
	//清空购物车
	$(".Cart_top span").click(function(){
		$(".Cart_list").find("li").remove();
		$(".floor").removeClass("floorOn");
		$(".qty").hide(500);
		$('#total_price').text(0);
		$(".Cart").slideUp();
		$(".Mask").hide();
		$('.foodright .Addsub').find('input').val(0);
		$('.foodright .Addsub').find('.jia').siblings().hide();
		goodsNumber = 0;
		goodsCartMoney = 0;
		goodsCart = [];
		stringifyCart();
	});
	
	//购物效果
	$(".trolley").toggle(function(){
		$(".Cart").slideDown();
		$(".Mask").show();
	},function(){
		$(".Cart").slideUp();
		$(".Mask").hide()
	});
	
	//弹出规格
	$(".Speci").click(function(){
		$(this).parents(".food_right").siblings(".TcancelT").slideDown();
		$(".Mask").show();
	});
	//关闭规格弹出
	$(".gb").click(function(){
		$(this).parents(".TcancelT").slideUp();
		$(".Mask").hide();
	});
	
	//规格中选项的选择
	$(document).on('click', '.fications li', function(){
		var father_obj = $(this).parents('.fications');
		var type = father_obj.data('type'), id = father_obj.data('id'), name = father_obj.data('name'), num = father_obj.data('num');
		var this_id = $(this).data('id'), this_name = $(this).data('name'), goods_id = parseInt($(this).data('goods_id'));
		if (num == 1) {
			$(this).addClass('on').siblings('li').removeClass('on');
		} else {
			$(this).toggleClass("on");
			if (father_obj.find('.on').length > num) {
				$(this).removeClass("on");
				motify.log('最多可以选择' + num + '个');
				return false;
			}
		}
		var select_html = '已选：';
		var spec_ids = [];
		$(this).parents('.TcancelT').find('.fications').each(function(dom){
			$(this).find('li').each(function(){
				if ($(this).hasClass('on')) {
					select_html += '<span>' + $(this).data('name') + '</span>';
					if ($(this).data('type') == 'spec') {
						spec_ids.push($(this).data('id'))
					}
				}
			});
		});

		$(this).parents(".TcancelT_zh").siblings(".Selected").html(select_html);
		if (type == 'spec' && spec_ids.length > 0) {
			var ALL_GOODS = $.parseJSON(all_goods);
			var price = 0;
			if (typeof(ALL_GOODS[goods_id][spec_ids.join('_')]) != 'undefined') {
				price = ALL_GOODS[goods_id][spec_ids.join('_')]['price'];
			}
			$(this).parents('.TcancelT').find('.TcancelT_topL span').html('<i>$</i>' + price);
			$(this).parents('.TcancelT').find('.join').data('price', price);
		}
	});
	
	
	//提交规格选中的
	$(document).on('click', '.TcancelT .join', function(){
		var goodsCartKey = $(this).data('goods_id'), goods_id = parseInt($(this).data('goods_id')), name = $(this).data('name'), price = parseFloat($(this).data('price'));
//		var pro_ids = [], spec_ids = [], spec_val_ids = [], pro_val_ids = [], details = [], names = [];
		var flag = false;
		var params = [];
		$(this).parents('.TcancelT').find('.fications').each(function(dom){
			var id = $(this).data('id'), name = $(this).data('name'), type = $(this).data('type'), num = $(this).data('num');
			var temp = {
					'type':type,
					'id':id,
					'name':name,
					'data':[]
			};
//			temp['type'] = type;
//			temp['id'] = id;
//			temp['name'] = name;
//			temp['data'] = [];
			
//			if (type == 'properties') {
//				pro_ids.push(id);
//				pro_val_ids[id] = [];
//			} else {
//				spec_ids.push(id);
//				spec_val_ids[id] = [];
//			}
//			var goodsDetail = name + ':', pre = '';
			var select_num = 0;
			$(this).find('li').each(function(){
				if ($(this).hasClass('on')) {
					temp['data'].push({'id':$(this).data('id'), 'name':$(this).data('name')});
//					if ($(this).data('type') == 'spec') {
//						spec_val_ids[id].push($(this).data('id'));
//					} else {
//						pro_val_ids[id].push($(this).data('id'));
//					}
//					if (names.length) {
//						names += ',' + $(this).data('name');
//					} else {
//						names += $(this).data('name');
//					}
//					names.push($(this).data('name'));
//					goodsDetail += pre + $(this).data('name');
//					pre = ',';
					select_num ++;
				}
			});
			if (select_num == 0) {
				flag = true;
				motify.log('必须在' + name + '下选择一项');
				return false;
			} else if (select_num > num) {
				flag = true;
				motify.log('必须在' + name + '下最多可选' + num + '项');
				return false;
			}
			params.push(temp);
//			details.push(goodsDetail);
		});
		if (flag) return false;
		
//		var spec_val_id_str = '';
//		if (spec_val_ids.length > 0) {
//			spec_val_id_str = spec_val_ids.join('_');
//			goodsCartKey += '_s_' + spec_val_id_str;
//		}
//		
//		if (false) {
//			return false;
//		}
//		
//		var pro_id_str = '';
//		if (pro_ids.length > 0) {
//			pro_id_str = pro_ids.join('_');
//			for (var t in pro_ids) {
//				goodsCartKey += '_p_' + pro_ids[t];
//				if (typeof(pro_val_ids[pro_ids[t]]) != 'undefined' && pro_val_ids[pro_ids[t]].length > 0) {
//					goodsCartKey += '_v_' + pro_val_ids[pro_ids[t]].join('_');
//				}
//			}
//		}
		
//		var names_str = names.join(',');
//		var name_detail = details.join(';');
		var names_str = '';
		if (params.length) {
			for (var pi in params) {
				if (params[pi].type == 'spec') {
					goodsCartKey += '_s_' + params[pi].id;
				}
				if (params[pi]['data'].length) {
					for (var di in params[pi]['data']) {
						goodsCartKey += '_v_' + params[pi]['data'][di].id;
						if (names_str.length > 0) {
							names_str += ',' + params[pi]['data'][di].name
						} else {
							names_str += params[pi]['data'][di].name;
						}
					}
				}
			}
		}
		
		if ($('.Cart_list ul').find('.goods_' + goodsCartKey).length) {
			var this_num = parseInt($('.goods_' + goodsCartKey).find("input").val());
		} else {
			var this_num = 0;
		}
		
		this_num ++;
		goodsNumber ++;
		goodsCartMoney += price;
		
//		for (var i in goodsCart) {
//			if (goodsCart[i].goodsCartKey == goodsCartKey) {
//				this_index = i;
//			}
//		}
		var this_index = null;
		for (var i in goodsCart) {
			var old_goodsCartKey = goodsCart[i].goods_id;
			if (goodsCart[i]['params'].length) {
				for (var pi in goodsCart[i]['params']) {
					if (goodsCart[i]['params'][pi].type == 'spec') {
						old_goodsCartKey += '_s_' + goodsCart[i]['params'][pi].id;
					}
					if (goodsCart[i]['params'][pi]['data'].length) {
						for (var di in goodsCart[i]['params'][pi]['data']) {
							old_goodsCartKey += '_v_' + goodsCart[i]['params'][pi]['data'][di].id;
						}
					}
				}
			}
			if (goodsCartKey == old_goodsCartKey) {
				this_index = i;
				break;
			}
		}
		
		
		if (this_index != null) {
			goodsCart[this_index].num = this_num;
		} else {
//			goodsCart.push({
//				'goods_id':goods_id,
//				'num':this_num,
//				'name':name,
//				'price':price,
//				'spec_val_id':spec_val_id_str,
//				'goodsCartKey':goodsCartKey,
//				'names_str':names_str,
//				'pro_id':pro_id_str,
//				'name_detail':name_detail});
			goodsCart.push({
				'goods_id':goods_id,
				'num':this_num,
				'name':name,
				'price':price,
				'params':params});
		}
		


		
		$('.goods_' + goodsCartKey).find("input").val(this_num);
		if (this_num > 0) {
			if (!$('.Cart_list ul').find('.goods_' + goodsCartKey).length) {
				var cart_goods_html = '';
				cart_goods_html += '<li class="clr goods_' + goodsCartKey + '">';
				cart_goods_html += '<div class="Clist_left">';
				cart_goods_html += '<h2>' + name + '</h2>';
				cart_goods_html += '<span>' + names_str + '</span>';
				cart_goods_html += '</div>';
				cart_goods_html += '<div class="Clist_right">';
				cart_goods_html += '<div class="MenuPrice"><i>$</i>' + price + '</div>';
				cart_goods_html += '<div class="Addsub">';
				cart_goods_html += '<a href="javascript:void(0)" class="jian" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
				cart_goods_html += '<input type="text" value="' + this_num + '" readOnly="true" class="num">';
				cart_goods_html += '<a href="javascript:void(0)" class="jia" data-price="' + price + '" data-id="' + goods_id + '" data-index="' + goodsCartKey + '" data-name="' + name + '"></a>';
				cart_goods_html += '</div>';
				cart_goods_html += '</div>';
				cart_goods_html += '</li>';
				$('.Cart_list ul').append(cart_goods_html);
			}
		} 
		if (goodsNumber > 0) {
			$(".floor").addClass("floorOn");
			$(".qty").show(500).text(goodsNumber);
			$('#total_price').text(goodsCartMoney);
		}
		stringifyCart();
		$(this).parents(".TcancelT").slideUp();
		$(".Mask").hide();
	});
	init_goods_menu();
	var is_submit = false;
	$(document).on('click', '.next', function(){
		if (goodsNumber < 1) {
			motify.log('您还没有点餐呢！');
			return false;
		}
		if (is_submit) return false;
		if(!$(this).hasClass('disabled')){
			$(this).addClass('disabled');
			document.cart_confirm_form.submit();
		}
		return false;
//		var data = {
//				'store_id':$('input[name=store_id]').val(),
//				'order_id':$('input[name=order_id]').val()
//		};
//		
//		$.post(submit_url, data, function(response){
//			if (response.err_code) {
//				motify.log(response.msg);
//				return false;
//			} else {
//				location.href = response.url;
//			}
//		}, 'json');
	});
});

function stringifyCart()
{
	var cookieProductCart = [];
	for(var i in goodsCart){
		if (goodsCart[i].num > 0) {
			cookieProductCart.push(goodsCart[i]);
		}
	}
	$.cookie(cookie_index, JSON.stringify(cookieProductCart), {expires:700,path:'/'});
}

function init_goods_menu()
{
	var nowShopCart = $.parseJSON($.cookie(cookie_index));
	goodsCart = [];
	var cart_goods_html = '';
	for (var i in nowShopCart) {
		if (nowShopCart[i] != null && nowShopCart[i].num > 0) {
			var detail_name = '', goodsCartKey = nowShopCart[i].goods_id;
			if (nowShopCart[i]['params'].length) {
				for (var pi in nowShopCart[i]['params']) {
					if (nowShopCart[i]['params'][pi].type == 'spec') {
						goodsCartKey += '_s_' + nowShopCart[i]['params'][pi].id;
					}
					if (nowShopCart[i]['params'][pi]['data'].length) {
						for (var di in nowShopCart[i]['params'][pi]['data']) {
							goodsCartKey += '_v_' + nowShopCart[i]['params'][pi]['data'][di].id;
							if (detail_name.length > 0) {
								detail_name += ',' + nowShopCart[i]['params'][pi]['data'][di].name
							} else {
								detail_name += nowShopCart[i]['params'][pi]['data'][di].name;
							}
						}
					}
				}
			}
			var tmp_extra_price = '';
			if(nowShopCart[i].extra_price>0&&open_extra_price==1){
				tmp_extra_price = '+'+nowShopCart[i].extra_price+nowShopCart[i].extra_price_name
			}
			cart_goods_html += '<li class="clr goods_' + goodsCartKey + '">';
			cart_goods_html += '<div class="Clist_left">';
			cart_goods_html += '<h2>' + nowShopCart[i].name + '</h2>';
			if (detail_name.length) {
				cart_goods_html += '<span>' + detail_name + '</span>';
			}
			
			cart_goods_html += '</div>';
			cart_goods_html += '<div class="Clist_right">';
			cart_goods_html += '<div class="MenuPrice"><i>$</i>' + nowShopCart[i].price +tmp_extra_price+ '</div>';
			cart_goods_html += '<div class="Addsub">';
			cart_goods_html += '<a href="javascript:void(0)" class="jian" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name +'"'+ 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name +'"></a>';
			cart_goods_html += '<input type="text" value="' + nowShopCart[i].num + '" readOnly="true" class="num">';
			cart_goods_html += '<a href="javascript:void(0)" class="jia" data-price="' + nowShopCart[i].price + '" data-id="' + nowShopCart[i].goods_id + '" data-index="' + goodsCartKey + '" data-name="' + nowShopCart[i].name+'"' + 'data-extra_pay_price="' + nowShopCart[i].extra_price +'" data-extra_price_name="' + nowShopCart[i].extra_price_name+'"></a>';
			cart_goods_html += '</div>';
			cart_goods_html += '</div>';
			cart_goods_html += '</li>';
			
			$('.goods_' + goodsCartKey).find("input").val(parseInt(nowShopCart[i].num)).show();
			$('.goods_' + goodsCartKey).find(".jian").show();
			goodsNumber += parseInt(nowShopCart[i].num);
			goodsCartMoney += parseFloat(nowShopCart[i].price) * parseInt(nowShopCart[i].num);
			if(nowShopCart[i].extra_price>0&&open_extra_price==1){
				goodsExtraPrice+=parseFloat(nowShopCart[i].extra_price) * parseInt(nowShopCart[i].num);
				var extra_price_name = nowShopCart[i].extra_price_name;
			}
			
			goodsCart[i] = nowShopCart[i];
		}
	}
	$('.Cart_list ul').append(cart_goods_html);
	if (goodsNumber > 0) {
		$(".floor").addClass("floorOn");
		$(".qty").show(500).text(goodsNumber);
		if(goodsExtraPrice>0){
			$('#total_price').text(goodsCartMoney+'+'+goodsExtraPrice+extra_price_name);
		}else{			
			$('#total_price').text(goodsCartMoney);
		}
	}
	
}
