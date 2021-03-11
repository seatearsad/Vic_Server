var nowPage='', storeTheme = 0;
var window_width = $(window).width();
var window_height = $(window).height();
var categoryList=null,sortList=null,typeList=null,choosePage='list';
var is_refresh = false;
$(function(){
	FastClick.attach(document.body);
	
	$('body').width(window_width);
	$('body').css({width:window_width,'min-height':window_height});
	$('.pageDiv').css({width:window_width,'min-height':window_height,'max-width':640});

    $(window).bind('hashchange',function(){
        hash_handle();
    });
	
	$(document).on('click','.hasMore',function(){
		$(this).toggleClass('showMore');
		return false;
	});
	
	/*页面点击事件*/
	$(document).on('click','.page-link',function(){
		redirectPage(ajax_url_root+'classic_'+$(this).data('url'));
		return false;
	});
	
	$(window).resize(function(){
		if($(window).width() != window_width){
			location.reload();
		}
	});
	//changeTitle('快店列表');
	
	hash_handle();
});

function redirectPage(url){
	pageLoadTips();
	window.addEventListener("pagehide", function(){
		pageLoadTipHide();
	},false);
	
	window.location.href = url;
}

function resetBodyHeight(){
	// console.log($('.nowPage'));
	// $('body').height($('.nowPage').height());
}
//-----入口---------
function hash_handle(){

    var locationHash = location.hash.replace("#","");
    console.log("hash_handle-->"+locationHash);
    if(locationHash == "search"){
        locationHashItem = locationHash;
	}else if(locationHash.split('-')[0] == "good") {
        var locationHashParam = locationHash.split('-');
        var locationHashItem = locationHashParam[0];
    }else{
        var locationHashParam = locationClassicHash.split('-');
        var locationHashItem = locationHashParam[0];
	}
    console.log("hash_handle-->locationHashItem->"+locationHashItem);
	if(locationHashItem != 'shop' && locationHashItem != 'good' && locationHashItem != "search"){
		changeWechatShare('plat');
	}

	switch(locationHashItem){
		case 'shop':
			if(locationHashParam.length == 1 || isNaN(parseInt(locationHashParam[1])) || parseInt(locationHashParam[1]) == 0){
				location.hash = 'list';
			}else{
				showShop(locationHashParam[1]);
			}
			break;
		case 'cat':
			showCategory(locationHashParam[1]);
			break;
		case 'address':
			showAddress();
			break;
		case 'shopsearch':
			showShopSearch();
			break;
		case 'map':
			showMap(locationHashParam[1],locationHashParam[2],locationHashParam[3],locationHashParam[4],locationHashParam[5]);
			break;
		case 'good':	//打开商品详情
			showGood(locationHashParam[1],locationHashParam[2]);
			break;
		case 'search':	//店铺搜索
			showSearch();
			break;
		default:
			showList();
	}
}

//显示分类
var isShowCat = false,comeInShop = false,isCatListShow=false;
function showCategory(tmpCatUrl){
	if(comeInShop == false){
		pageLoadTips();
	}
	$('#pageCat').addClass('nowPage').show();

	close_dropdown();
	
	if(comeInShop == false){
		$('#listNavBox').after('<div id="listNavBoxPlace"></div>');
		$('#pageCatNav')[0].appendChild($('#listNavBox')[0]);
	}
	choosePage = 'cat';
	
	if(isShowCat == false){
		$('#catBackBtn').click(function(){
			goBackPage();
		});
		$(document).on('click','#pageCat #storeList dd',function(){
			comeInShop = true;
		});
		/*防止重复初始化JS*/
		if(motify.checkIos()){
			$('body').on('touchmove',function(){
				if(isShowShade == false){
					scrollListEvent('ios');
				}
			});
			$(window).scroll(function(){
				$('body').trigger('touchmove');
			});
		}else{
			$(window).scroll(function(){
				scrollListEvent('android');
			});
		}
		function scrollListEvent(phoneType){	
			if(nowPage == 'cat'){
				if(isShowShade == true){
					close_dropdown();
					return false;
				}
				if(isCatListShow == false && catHasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
					showCatShopList();
				}
			}
		}
		if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
			if(motify.checkAndroid()){
				var locations = window.lifepasslogin.getLocation(false);
				var locationArr = locations.split(',');
				user_long = $.trim(locationArr[0]);
				user_lat = $.trim(locationArr[1]);
			}else{
				$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
			}
		}else if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
			user_long = $.cookie('userLocationLong');
			user_lat = $.cookie('userLocationLat');
		}else{
			redirectPage(ajax_url_root+'classic_address');
		}
		
		
		if((user_long == 0 || user_lat == 0) && $.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
			user_long = $.cookie('userLocationLong');
			user_lat = $.cookie('userLocationLat');
			$('#locationText').html($.cookie('userLocationName'));
			pageLoadHides();
			showShopList(true);
		}
		
		isShowCat = true;
	}
	
	cat_url = tmpCatUrl ? tmpCatUrl : 'all';
	if(categoryList == null || sortList == null || typeList == null){
		$.getJSON(ajax_url_root+'ajax_category',function(result){
			/*可选分类*/
			if(result.category_list){
				categoryList = result.category_list;
			}
			/*可选排序*/
			if(result.sort_list){
				sortList = result.sort_list;
				sort_url = sortList[0].sort_url;
			}
			/*可选类别*/
			if(result.type_list){
				typeList = result.type_list;
				type_url = typeList[0].type_url;
			}
			
			laytpl($('#listCategoryListTpl').html()).render(categoryList, function(html){
				$('#dropdown_scroller .category-wrapper ul').html(html);
			});
			laytpl($('#listSortListTpl').html()).render(sortList, function(html){
				$('#dropdown_scroller .sort-wrapper ul').html(html);
			});
			laytpl($('#listTypeListTpl').html()).render(typeList, function(html){
				$('#dropdown_scroller .type-wrapper ul').html(html);
			});
			
			var tmpCatDom = $('#listNavBox .category-wrapper .listCat-'+cat_url);
			if(tmpCatDom.size() == 0 || tmpCatDom.size() > 2){
				tmpCatDom = $('#listNavBox .category-wrapper .listCat-all');
			}
			if(tmpCatDom.size() == 1){
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url));
			}else if(tmpCatDom.size() == 2){
				$('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(0)').trigger('click');
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(1)'));
			}
		});
	}else{
		if(comeInShop == false){
			$('.dropdown-toggle.sort span').html(categoryList[0].cat_name);
			laytpl($('#listCategoryListTpl').html()).render(categoryList, function(html){
				$('#dropdown_scroller .category-wrapper ul').html(html);
			});
			
			sort_url = sortList[0].sort_url;
			$('.dropdown-toggle.sort span').html(sortList[0].name);
			laytpl($('#listSortListTpl').html()).render(sortList, function(html){
				$('#dropdown_scroller .sort-wrapper ul').html(html);
			});
			
			type_url = typeList[0].type_url;
			$('.dropdown-toggle.type span').html(typeList[0].name);
			laytpl($('#listTypeListTpl').html()).render(typeList, function(html){
				$('#dropdown_scroller .type-wrapper ul').html(html);
			});
				
			var tmpCatDom = $('#listNavBox .category-wrapper .listCat-'+cat_url);
			if(tmpCatDom.size() == 0){
				tmpCatDom = $('#listNavBox .category-wrapper .listCat-all');
			}
			if(tmpCatDom.size() == 1){
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url));
			}else if(tmpCatDom.size() == 2){
				$('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(0)').trigger('click');
				list_location($('#listNavBox .category-wrapper .listCat-'+cat_url+':eq(1)'));
			}
		}else{
			comeInShop = false;
		}
	}
}

//显示地图
var hasLoadMap=false;
function showMap(shopId,lng,lat,shopName,address){
	pageLoadTips();
	nowPage = 'map';
	$('#pageMap').addClass('nowPage').show().siblings('.pageDiv').removeClass('nowPage').hide();
	if(hasLoadMap == false){
		$('#shopDetailMapBiz').height(window_height-60);
		$('#shopDetailMapClose').click(function(){
			$(this).hide();
			goBackPage();
		});
		$('#shopDetailMapAddressGo').click(function(){
			if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
				window.lifepasslogin.startToNavigation(lng,lat,shopName);
			}else if(typeof(wxSdkLoad) != "undefined"){
				pageLoadTips();
				$.getJSON(baiduToGcj02Url+"&baidu_lat="+lat+"&baidu_lng="+lng,function(result){
					pageLoadHides();
					if(result['status'] == 1){
						wx.ready(function (){
							wx.openLocation({
								latitude: result['info']['lat'],
								longitude: result['info']['lng'],
								name: decodeURIComponent(shopName), // 位置名
								address: decodeURIComponent(address), // 地址详情说明
								scale: 18, // 地图缩放级别,整形值,范围从1~28。默认为最大
								infoUrl: window.location.href // 在查看位置界面底部显示的超链接,可点击跳转
							});
						});
					}else{
						window.location.href = get_route_url+'&store_id='+shopId;
					}
				})
			}else{
				window.location.href = get_route_url+'&store_id='+shopId;
			}
		});

		hasLoadMap = true;
	}
	$('#shopDetailMapClose').show();
	$('#shopDetailMapAddress').html(decodeURIComponent(address));
	
	var map = new BMap.Map("shopDetailMapBiz",{enableMapClick:false});
	map.centerAndZoom(new BMap.Point(lng,lat), 16);
	
	map.addControl(new BMap.ZoomControl());  //添加地图缩放控件
	var marker1 = new BMap.Marker(new BMap.Point(lng,lat));  //创建标注
	map.addOverlay(marker1);                 // 将标注添加到地图中
	//创建信息窗口
	var infoWindow1 = new BMap.InfoWindow(decodeURIComponent(shopName));
	marker1.openInfoWindow(infoWindow1);
	marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});


	pageLoadHides();
}

var listShopList=[],listNavBarTop=0,isShowShade = false,mustShowShopList=false,isListShow = false,isFirstShowList = true;
//显示列表
function showList(){
	pageLoadTips();
	
	$('#pageList').addClass('nowPage').show();
	/*滚动条事件*/
	if(isFirstShowList == true){
		var listHeaderColor = $('#listHeader').css('background-color').match(/\(.*\)/);
		var listHeaderColor = listHeaderColor[0].replace('(','').replace(')','');
		$('#listHeader').css('background-color','rgba('+listHeaderColor+',0)');
		
		listNavBarTop = $('#listNavBox').offset().top - 50;
		/*防止重复初始化JS*/
		if(motify.checkIos()){
			$('body').on('touchmove',function(){
				if(isShowShade == false){
					scrollListEvent('ios');
				}
			});
			$(window).scroll(function(){
				$('body').trigger('touchmove');
			});
		}else{
			$(window).scroll(function(){
				scrollListEvent('android');
			});
		}
		function scrollListEvent(phoneType){
			if(nowPage == 'list' || nowPage == ''){
				if(isShowShade == true){
					close_dropdown();
					return false;
				}
				var scrollTop = $(window).scrollTop();
				if(scrollTop > 50){
					$('#listHeader').removeClass('roundBg');
				}else{
					$('#listHeader').addClass('roundBg');
				}
				if(scrollTop > 150){
					$('#listHeader').css('background-color','rgb('+listHeaderColor+')');
				}else{
					$('#listHeader').css('background-color','rgba('+listHeaderColor+','+(scrollTop/100)+')');
				}
				if(scrollTop >= listNavBarTop){
					$('#listNavBox').addClass('fixed');
					$('#listNavPlaceHolderBox').show();
				}else{
					$('#listNavBox').removeClass('fixed');
					$('#listNavPlaceHolderBox').hide();
				}
				
				if(isListShow == false && listHasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
					showShopList();
				}
			}
		}
		
		if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
			if(motify.checkAndroid()){
				var locations = window.lifepasslogin.getLocation(false);
				var locationArr = locations.split(',');
				user_long = $.trim(locationArr[0]);
				user_lat = $.trim(locationArr[1]);
				$('#locationText').html($.trim(locationArr[2]));
				$('#listBackBtn').removeClass('hide').click(function(){
					window.lifepasslogin.webViewGoBack();
				});
				pageLoadHides();
				showShopList(true);
				showListData();
			}else{
				$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
				$('#listBackBtn').removeClass('hide').click(function(){
					$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>'); 
				});
			}
		}else if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
			user_long = $.cookie('userLocationLong');
			user_lat = $.cookie('userLocationLat');
			$('#locationText').html($.cookie('userLocationName'));
			pageLoadHides();
			showShopList(true);
			showListData();
		}else{
			redirectPage(ajax_url_root+'classic_address');
		}
		isFirstShowList = false;
	}else{
		if(user_long == '0'){
			getListGeocoderError();
		}else if(mustShowShopList == true){
			mustShowShopList = false;
			showShopList(true);
		}
		pageLoadHides();
	}
}

function callbackLocation(locations){
	var locationArr = locations.split(',');
	user_long = $.trim(locationArr[0]);
	user_lat = $.trim(locationArr[1]);
	if(locationClassicHash == 'address'){
		$('#pageAddressLocationList').show().find('.content dd').data({'long':$.trim(locationArr[0]),'lat':$.trim(locationArr[1]),'name':$.trim(locationArr[2])}).find('.name').html($.trim(locationArr[2]));
	}else if(locationClassicHash == 'index'){
		$('#locationText').html($.trim(locationArr[2]));
		pageLoadHides();
		showShopList(true);
		showListData();
	}
}

function getListGeocoderError(){
	pageLoadHides();
	var addressTipLayer = layer.open({
		content: 'Locating failed, please enter your address to start.',
		btn: ['OK'],
		end: function(){
			$('#pageAddressHeader').addClass('mustHideBack');
			layer.close(addressTipLayer);
			location.hash = 'address';
		}
	});
}
/*店铺搜索*/
var isShowShopSearch = false,loadShopSearchTimer=null,isSearchListShow=true;
function showShopSearch(){
	pageLoadTips({showBg:false});
	nowPage = 'shopSearch';
	$('#pageShopSearch').addClass('nowPage').show().siblings('.pageDiv').removeClass('nowPage').hide();
	
	if(isShowShopSearch == false){
		if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
			if(motify.checkAndroid()){
				var locations = window.lifepasslogin.getLocation(false);
				var locationArr = locations.split(',');
				user_long = $.trim(locationArr[0]);
				user_lat = $.trim(locationArr[1]);
			}else{
				$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
			}
		}else if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
			user_long = $.cookie('userLocationLong');
			user_lat = $.cookie('userLocationLat');
		}else{
			redirectPage(ajax_url_root+'classic_address');
		}
		
		$('#pageShopSearchTxt').width(window_width-124-32);
		
		$('#pageShopSearchBackBtn').click(function(){
			goBackPage();
		});
		
		$("#pageShopSearchTxt").bind('input', function(e){
			var address = $.trim($(this).val());
			if(address.length > 0){
				$('#pageShopSearchDel').show();
				$('#pageShopSearchBtn').addClass('so');
			}else{
				$('#pageShopSearchDel').hide();
				$('#pageShopSearchBtn').removeClass('so');
			}
		});
		$('#pageShopSearchBtn').click(function(){
			var address = $.trim($("#pageShopSearchTxt").val());
			if(address == ''){
				motify.log('Please enter store name.');
			}else{
				isSearchListShow = false;
				if(user_long == '0'){
					getUserLocation({okFunction:'getListGeocoderbefore',okFunctionParam:[true],errorFunction:'getListGeocoderError',errorFunctionParam:[false]});
				}else{
					showShopSearchList(true);
				}
			}
		});
		
		$('#pageShopSearchDel').click(function(){
			$('#pageShopSearchTxt').val('').trigger('input');
		});
		
		/*防止重复初始化JS*/
		if(motify.checkIos()){
			$('body').on('touchmove',function(){
				if(isShowShade == false){
					scrollSearchListEvent('ios');
				}
			});
			$(window).scroll(function(){
				$('body').trigger('touchmove');
			});
		}else{
			$(window).scroll(function(){
				scrollSearchListEvent('android');
			});
		}
		function scrollSearchListEvent(phoneType){	
			if(nowPage == 'shopSearch'){				
				if(isSearchListShow == false && shopSearchListHasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
					showShopSearchList();
				}
			}
		}
		
		isShowShopSearch = true;
	}
	
	pageLoadHides();
}

/*收货地址*/
var hasLoadAddress=false,loadAddressTimer=null,addressGeocoder = false;
function showAddress(){
	pageLoadTips({showBg:false});

	$('#pageAddress').addClass('nowPage').show();
	
	if($.cookie('userLocationName') && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
		$('#pageAddressHeader').removeClass('hideBack');
		//$('#pageAddressSearchTxt').width(window_width-124-32);
		$('#pageAddressNowLocation').show().find('.content dd').data({'long':$.trim($.cookie('userLocationLong')),'lat':$.cookie('userLocationLat'),'name':$.cookie('userLocationName')}).find('.name').html($.cookie('userLocationName'));
	}else{
		$('#pageAddressNowLocation').remove();
	}
	addressGeocoder = true;
	
	if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
		if(motify.checkAndroid()){
			var locations = window.lifepasslogin.getLocation(false);
			$('#pageAddressLocationList').show().find('.content dd').data({'long':$.trim(locationArr[0]),'lat':$.trim(locationArr[1]),'name':$.trim(locationArr[2])}).find('.name').html($.trim(locationArr[2]));
		}else{
			$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
		}
	}else{
		getUserLocation({'useHistory':false,okFunction:'getListGeocoderbefore',okFunctionParam:[true],errorFunction:'getAddressGeocoderError',errorFunctionParam:[false]});
	}
	
	if(hasLoadAddress == false){
		$('#pageAddressBackBtn').click(function(){
			goBackPage();
		});
		
		$("#pageAddressSearchTxt").bind('input', function(e){
			var address = $.trim($(this).val());
			if(address.length > 0){
				$('#pageAddressSearchDel,#pageAddressSearchContent').show();
				$('#pageAddressContent').hide();
				
				clearTimeout(loadAddressTimer);
				loadAddressTimer = setTimeout("searchAddress('"+address+"')", 500);
				$('#pageAddressSearchBtn').addClass('so');
			}else{
				$('#pageAddressSearchDel').hide();
				$('#pageAddressSearchBtn').removeClass('so');
				
				$('#pageAddressContent').show();
				$('#pageAddressSearchContent').hide();
			}
		});
		$('#pageAddressSearchBtn').click(function(){
			var address = $.trim($("#pageAddressSearchTxt").val());
			searchAddress(address);
		});
		
		$('#pageAddressSearchDel').click(function(){
			$('#pageAddressSearchTxt').val('').trigger('input');
			/* $('#pageAddressSearchDel').hide(); */
		});
		
		$(document).on('click','.searchAddressList dd',function(){
			$('#pageAddressSearchDel').trigger('click');
			user_long = $(this).data('long');
			user_lat = $(this).data('lat');
			$('#locationText').html($(this).data('name'));
			
			$.cookie('userLocation',user_long+','+user_lat,{expires:700,path:'/'});
			$.cookie('userLocationLong',user_long,{expires:700,path:'/'});
			$.cookie('userLocationLat',user_lat,{expires:700,path:'/'});
			$.cookie('userLocationName',$(this).data('name'),{expires:700,path:'/'});
			if($(this).data('id')){
				$.cookie('userLocationId',$(this).data('id'),{expires:700,path:'/'});
			}	
			mustShowShopList = true;
			redirectPage(locationLastPage);
			return false;
		});
		
		$.getJSON(ajax_url_root+'ajax_address',function(result){
			if(result.length > 0){
				laytpl($('#listAddressListTpl').html()).render(result, function(html){
					$('#pageAddressUserList .content').html(html);
				});
			}else{
				$('#pageAddressUserList').hide();
			}
			pageLoadHides();
		});
		
		hasLoadAddress = true;
	}else{
		pageLoadHides();
	}
}

function getAddressGeocoderError(){
	$('#pageAddressLocationList dl').html('<div style="height:40px;line-height:40px;background:white;padding-left:12px;">Address not found</div>');
}

function searchAddress(address){
	$.get(ajax_map_url, {query:address}, function(data){
		if(data.status == 1){
			$('#pageAddressSearchContent dl').empty();
			var result = data.result;
			var addressHtml = '';
			for(var i=0;i<result.length;i++){
				if(result[i]['long']){
					addressHtml += '<dd data-long="'+result[i]['long']+'" data-lat="'+result[i]['lat']+'" data-name="'+result[i]['name']+'">';
					addressHtml += '<div class="name">'+result[i]['name']+'</div>';
					addressHtml += '<div class="desc">'+result[i]['address']+'</div>';
					addressHtml += '</dd>';
				}
			}
			$('#pageAddressSearchContent dl').html(addressHtml);
		}
	});
}

var isShowGood = false;
// function showGood(shop_id,product_id){
// 	shopId = shop_id;
// 	$('#pageShop').addClass('nowPage').show();
// 	pageLoadTips();
// 	$('body').css('overflow','hidden');
// 	$('#shopDetailPage').height(window_height-50);
//
// 	$('#shopHeader,#shopBanner,#shopMenuBar,#shopMerchantBox').hide();
//
// 	cartEventReg();
// 	$.getJSON(ajax_url_root+'ajaxShop',{store_id:shopId},function(result){
// 		nowShop = result;
// 		if(nowShop.store.tmpl == '0'){
// 			$('#shopDetailPageImgbox').css({height:window_width*500/900,width:window_width});
// 		}else if(nowShop.store.tmpl == '1'){
// 			$('#shopDetailPageImgbox').css({height:window_width,width:window_width});
// 		}
// 		$.getJSON(ajax_url_root+'ajax_goods',{goods_id:product_id},function(result){
// 			nowProduct = result;
// 			productPicList = [];
// 			for(var i in result.pic_arr){
// 				productPicList.push(result.pic_arr[i].url);
// 			}
//
// 			//changeWechatShare('good',{title:nowProduct.name,desc:nowProduct.des,imgUrl:productPicList[0],link:goodShareUrl});
//
// 			// console.log(productPicList);
// 			laytpl($('#productSwiperTpl').html()).render(result.pic_arr, function(html){
// 				$('#shopDetailPageImgbox .swiper-wrapper').removeAttr('style').html(html);
// 				if(productSwiper != null){
// 					productSwiper.reInit();
// 				}
// 				if(result.pic_arr.length > 1){
// 					productSwiper = $('#shopDetailPageImgbox').swiper({
// 						pagination:'#shopDetailPageImgbox .swiper-pagination-productImg',
// 						loop:true,
// 						grabCursor: true,
// 						paginationClickable: true,
// 						autoplay:3000,
// 						autoplayDisableOnInteraction:false,
// 						simulateTouch:false
// 					});
// 				}
// 			});
// 			$('#shopDetailPageTitle .title').html(result.name);
//             //$('#shopDetailPageTitle .desc').html(getLangStr('_MONTH_SALE_NUM_' , result.sell_count)+ ' ' +getLangStr('_PRAISE_TXT_')+result.reply_count);
//             $('#shopDetailPageTitle .desc').html(getLangStr('_PRAISE_TXT_')+result.reply_count);
// 			$('#shopDetailPageFormat').empty();
// 			if(result.des != ''){
// 				$('#shopDetailPageContent .content').html(result.des).show();
// 				$('#shopDetailPageContent').show();
// 			}else if(nowShop.store.delivery){
// 				$('#shopDetailPageContent .content').html(getLangStr('_REMINDER_STRING_')).show();
// 				$('#shopDetailPageContent').show();
// 			}else{
// 				$('#shopDetailPageContent').hide();
// 			}
// 			$('#shopDetailPagePrice').html('$'+result.price+'<span class="unit"><em>/ </em>'+result.unit+'</span>'+(result.stock_num != -1 ? '<span data-stock="'+result.stock_num+'">还剩'+result.stock_num+result.unit+'</span>' : '<span data-stock="-1"></span>') + '<span>'+ result.deposit_price +'</span>');
// 			if(result.properties_list){
// 				laytpl($('#productPropertiesTpl').html()).render(result.properties_list, function(html){
// 					$('#shopDetailPageLabelBox').html(html);
// 				});
// 				$('#shopDetailPageLabel').show();
// 			}else{
// 				$('#shopDetailPageLabel').hide();
// 			}
// 			if(result.spec_list){
// 				laytpl($('#productFormatTpl').html()).render(result.spec_list, function(html){
// 					$('#shopDetailPageFormat').html(html);
// 				});
// 			}
// 			$('#shopDetailPageNumber .number').addClass('productNum-'+result.goods_id);
//
// 			var nowShopCart = $.cookie('shop_cart_'+nowShop.store.id);
// 			if(nowShopCart){
// 				nowShopCartArr = $.parseJSON(nowShopCart);
// 				productCart=[];
// 				if(nowShopCartArr.length > 0){
// 					productCartNumber = productCartMoney = 0;
// 					for(var i in nowShopCartArr){
// 						var tmpSpec = [];
// 						var tmpObj = nowShopCartArr[i].productParam;
// 						if(tmpObj.length > 0){
// 							for(var j in tmpObj){
// 								if(tmpObj[j].type == 'spec'){
// 									tmpSpec.push(tmpObj[j].id);
// 								}else{
// 									for(var k in tmpObj[j].data){
// 										tmpSpec.push(tmpObj[j].data[k].list_id);
// 										tmpSpec.push(tmpObj[j].data[k].id);
// 									}
// 								}
// 							}
// 							if(tmpSpec.length > 0){
// 								var tmpSpecStr = nowShopCartArr[i].productId + '_' + tmpSpec.join('_');
// 								productCart[tmpSpecStr] = nowShopCartArr[i];
// 							}else{
// 								productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
// 							}
// 						}else{
// 							productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
// 							$('.product_'+nowShopCartArr[i].productId+' .plus').after('<div class="product_btn number productNum-'+nowShopCartArr[i].productId+'">'+nowShopCartArr[i].count+'</div>').after('<div class="product_btn min"></div>');
// 						}
// 						productCartNumber += nowShopCartArr[i].count;
// 						productCartMoney += nowShopCartArr[i].count * nowShopCartArr[i].productPrice;
// 					}
//
// 					//统计购物车功能
// 					cartFunction('count');
// 				}
// 				// console.log(productCart);
// 			}else{
// 				cartFunction('count');
// 			}
//
// 			$('#shopDetailPageImgbox').click(function(){
// 				if(motify.checkWeixin()){
// 					wx.previewImage({
// 						current:productPicList[0],
// 						urls:productPicList
// 					});
// 				}
// 			});
// 			$(document).on('click','#shopDetailPageFormat li',function(event){
// 				$(this).addClass('active').siblings('li').removeClass('active');
// 				changeProductSpec();
// 			});
//
// 			$(document).on('click','#shopDetailPageLabel li',function(event){
// 				var maxSize = $(this).closest('.row').data('num');
// 				if(maxSize == 1){
// 					$(this).addClass('active').siblings('li').removeClass('active');
// 				}else if(!$(this).hasClass('active')){
// 					var tmpActiveSize = $(this).closest('ul').find('.active').size();
// 					if(tmpActiveSize >= maxSize){
// 						motify.log($(this).closest('.row').data('label_name')+' 您最多能选择 '+maxSize+' 个');
// 					}else{
// 						/* if(tmpActiveSize == maxSize-1){
// 							motify.log('您最多能选择 '+maxSize+' 个，现在已经选择满了');
// 						} */
// 						$(this).addClass('active');
// 					}
// 				}else{
// 					$(this).removeClass('active');
// 				}
// 				changeProductSpec();
// 			});
//
// 			$(document).on('click','#shopDetailPageNumber .product_btn.plus,#shopDetailPageBuy',function(event){
// 				if(nowShop.store.is_close == 1){
// 					motify.log(getLangStr('_SHOP_AT_REST_'));
// 					return false;
// 				}
// 				var intStock = parseInt($('#shopDetailPagePrice span').data('stock'));
// 				if(intStock != -1 && (intStock == 0 || intStock - parseInt($('#shopDetailPageNumber .number').html()) <= 0)){
// 					motify.log(getLangStr('_NO_STOCK_'));
// 					return false;
// 				}
// 				tmpDomObj = $(this);
// 				if(!(motify.checkApp() && motify.checkAndroid())){
// 					flyer.fly({
// 						start: {
// 							left: event.pageX-10,
// 							top: event.pageY-120
// 						},
// 						end: {
// 							left: 20,
// 							top: window_height-50,
// 							width: 20,
// 							height: 20
// 						},
// 						onEnd:function(){
// 							cartFunction('plus',tmpDomObj,'productPage');
// 							flyer.remove();
// 						}
// 					});
// 				}else{
// 					cartFunction('plus',tmpDomObj,'productPage');
// 				}
// 				return false;
// 			});
// 			$(document).on('click','#shopDetailPageNumber .product_btn.min',function(event){
// 				tmpDomObj = $(this);
// 				cartFunction('min',tmpDomObj,'productPage');
// 				return false;
// 			});
//
// 			$('#shopDetailpageClose').click(function(){
// 				goBackPage();
// 			});
//
// 			changeProductSpec();
//
// 			// shopDetailPageIscroll = new IScroll('#shopDetailPage', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false});
// 			$('#shopContentBar').show();
// 			$('#shopDetailPage').show();
// 			pageLoadHides();
// 		});
//         // if(nowShop.shop_remind != ''){
//         //     pageLoadHides();
//         //     var remindTipLayer = layer.open({
//         //         content: nowShop.shop_remind,
//         //         btn: ['Confirm'],
//         //         end: function(){
//         //             layer.close(remindTipLayer);
//         //         }
//         //     });
//         // }
// 	});
// }

function showStoreCategory() {
    //$('body').css('overflow','hidden');
    var scrollRightTop =document.documentElement.scrollTop;

    $('#shopContentBar').show();
    $('#shopProductLeftBar2').css("top","140px");
    $('#shopCatBar').show().scrollTop(0).addClass('sliderLeft');

}

function showSearch() {
    if(nowPage != 'shop'){
        //location.hash = 'shop-'+shop_id;
        window.history.go(-1);
        return false;
    }

    pageLoadTips();
    $('body').css('overflow','hidden');
    //$('#shopSearchPage').height(window_height);
    $('#shopSearchPage').css('height','100%');
    $('#shopContentBar').show();
    $('#shopSearchPage').show().scrollTop(0).addClass('sliderLeft');
    $('#shopSearchPage').css("z-index",99);
    nowPage = 'search';
    is_refresh = false;
    pageLoadHides();
}

//spec对话框
function showGood(shop_id,product_id){
    //console.log("showGood>>nowPage=="+nowPage);
    if(nowPage != 'shop' && nowPage != 'search'){
        //location.hash = 'shop-'+shop_id;
		window.history.go(-1);
        return false;
    }

    pageLoadTips();
    $('body').css('overflow','hidden');
    //$('#shopDetailPage').height(500);
    $('#shopDetailPage').css('height','100%');
    if(nowShop.store.store_theme == '0'){
        //$('#shopDetailPageImgbox').css({height:window_width*500/900,width:window_width});
    }else if(nowShop.store.store_theme == '1'){
        //$('#shopDetailPageImgbox').css({height:window_width,width:window_width});
    }

    if(product_id == nowProduct.goods_id){
        $('#shopContentBar').show();
        $('#shopDetailPage').show().scrollTop(0).addClass('sliderLeft');
        $('#shopDetailPage').css("z-index",100);
        pageLoadHides();
    }else{

        $.getJSON(ajax_url_root+'ajax_goods',{goods_id:product_id},function(result) {
            nowProduct = result;
            productPicList = [];
            for (var i in result.pic_arr) {
                productPicList.push(result.pic_arr[i].url);
            }
            if (typeof(result.pic_arr) != 'undefined') {

            	//头图
				laytpl($('#productSwiperTpl').html()).render(result.pic_arr, function (html) {
					$('#shopDetailPageImgbox .swiper-wrapper').removeAttr('style').html(html);
					if (productSwiper != null) {
						productSwiper.reInit();
						productSwiper.swipeTo(0);
					}
					if (productSwiper == null && result.pic_arr.length > 1) {
						productSwiper = $('#shopDetailPageImgbox').swiper({
							pagination: '#shopDetailPageImgbox .swiper-pagination-productImg',
							loop: true,
							grabCursor: true,
							paginationClickable: true,
							autoplay: 3000,
							autoplayDisableOnInteraction: false,
							simulateTouch: false
						});
					}
				});
			}else{
                $('#shopDetailPageImgbox .swiper-wrapper').removeAttr('style').html("");
			}
            $('#shopDetailPageTitle .title').html(result.name);
            //$('#shopDetailPageTitle .desc').html(getLangStr('_MONTH_SALE_NUM_' , result.sell_count)+ ' ' +getLangStr('_PRAISE_TXT_')+result.reply_count);
            //$('#shopDetailPageTitle .desc').html(getLangStr('_PRAISE_TXT_')+result.reply_count);
            var showStr = "*This item is only available";
			if(result.is_time == 1){
                showStr = showStr + " from "+result.begin_time+" to "+result.end_time;
			}
			var weekStr = result.weekStr;

            showStr = showStr + " " + weekStr;
			if(result.is_time == 1 || result.is_weekshow == 1)
                $('#shopDetailPageTitle .desc').html(showStr);

            $('#shopDetailPageFormat').empty();
            $('#shopDetailPageDish').empty();
            if(result.des != ''){
                $('#shopDetailPageTitle .content').html(result.des).show();
                //$('#shopDetailPageTitle').show();
            }else if(nowShop.store.delivery){
                //$('#shopDetailPageContent .content').html(getLangStr('_REMINDER_STRING_')).show();
                //$('#shopDetailPageContent').show();
                //$('#shopDetailPageContent').hide();
            }else{
                //$('#shopDetailPageContent').hide();
            }
            $('#shopDetailPagePrice').html('$'+result.price+(result.extra_pay_price>0?'+'+result.extra_pay_price+result.extra_pay_price_name:'')+'<span class="unit"><em>/ </em>'+result.unit+'</span>'+(result.stock_num != -1 ? '<span class=\'stock_span\' data-stock="'+result.stock_num+'">Stock:'+result.stock_num+'</span>' : '<span data-stock="-1"></span>') + (result.deposit_price > 0 ? '<span>(Deposit:$'+ result.deposit_price +')</span>' : ''));

            if(result.properties_list){
                laytpl($('#productPropertiesTpl').html()).render(result.properties_list, function(html){
                    $('#shopDetailPageLabelBox').html(html);
                });
                $('#shopDetailPageLabel').show();
            }else{
                $('#shopDetailPageLabel').hide();
            }

            if(result.spec_list){
                laytpl($('#productFormatTpl').html()).render(result.spec_list, function(html){
                    $('#shopDetailPageFormat').html(html);
                });
                $('#shopDetailPageFormat').show();
            }else{
                $('#shopDetailPageFormat').hide();
			}

            if(result.side_dish){
				laytpl($('#productDishTpl').html()).render(result.side_dish, function (html) {
					$('#shopDetailPageDish').html(html);
				});
            }
            $('#shopDetailPageNumber .number').addClass('productNum-'+result.goods_id);

            changeProductSpec();

            // shopDetailPageIscroll = new IScroll('#shopDetailPage', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false});
            $('#shopContentBar').show();
            $('#shopDetailPage').show().scrollTop(0).addClass('sliderLeft');
            $('#shopDetailPage').css("z-index",100);
            $('.content_video').css({'width':$(window).width()-20,'height':($(window).width()-20)*9/16});
            pageLoadHides();
        });
    }
    nowPage = 'goods';
    is_refresh = false;
}

function cartEventReg(){
	$(document).on('click','#shopProductCartDel',function(){
		layer.open({
            content: getLangStr('_IS_CLEAR_CART_'),
            btn: [getLangStr('_CONFIRM_TXT_'), getLangStr('_CANCEL_TXT_')],
			shadeClose: false,
			yes: function(){
				$('#shopProductRightBar2 .product_btn.min,#shopProductRightBar2 .product_btn.number,#shopProductBottomBar .product_btn.min,#shopProductBottomBar .product_btn.number').remove();
				$('#shopDetailPageBuy').show();
				$('#shopDetailPageNumber').hide();
				productCart = [];
				productCartNumber = 0;
				productCartMoney = 0;
				cartFunction('count');//7
				layer.closeAll();
				$('#shopProductCartShade').trigger('click');
			}, no: function(){
				
			}
		});
	});
	
	$('#cartInfo').click(function(){

		if(!$(this).hasClass('isShow')){
			$(this).addClass('isShow');
			$('#shopProductCartShade').show();
			$('#shopProductCartBox').css('max-height',(window_height-50)/3*2+'px');
			laytpl($('#productCartBoxTpl').html()).render(productCart, function(html){
				//console.log(productCart);
				$('#shopProductCartBox').html(html);
				$('body').css('overflow-y','hidden');
			});
		}else{
			$('#shopProductCartShade').trigger('click');
		}
		// $('#shopPageShade').trigger('click');
	});
	
	$('#shopProductCartShade').click(function(){
		$(this).hide();
		$('#shopProductCartBox').empty();
		$('#cartInfo').removeClass('isShow');
		$('body').css('overflow-y','auto');
	});
	$(document).on('click','#shopProductCartBox .product_btn.plus',function(event){
		if(nowShop.store.is_close == 1 || nowShop.store.store_status=='0'){
			motify.log(getLangStr('_SHOP_AT_REST_'));
			return false;
		}
		tmpDomObj = $(this);
		cartFunction('plus',tmpDomObj,tmpDomObj.closest('dd'));
	});
	$(document).on('click','#shopProductCartBox .product_btn.min',function(event){
		tmpDomObj = $(this);
		cartFunction('min',tmpDomObj,tmpDomObj.hasClass('cart') ? tmpDomObj.closest('dd') : tmpDomObj.closest('li'));
		return false;
	});
	
	
	$('#checkCart').click(function(){
        pageLoadTips({showBg:false});
        //alert(check_cart_url+'&store_id='+nowShop.store.id);
		window.location.href = check_cart_url+'&store_id='+nowShop.store.id+"&from=shop";
	});
}

//========获得div相对浏览器的坐标
function CPos(x, y)
{
    this.x = x;
    this.y = y;
}
function GetObjPos(ATarget)
{
    var target = ATarget;
    var pos = new CPos(target.offsetLeft, target.offsetTop);

    var target = target.offsetParent;
    while (target)
    {
        pos.x += target.offsetLeft;
        pos.y += target.offsetTop;

        target = target.offsetParent
    }
    return pos;
}

var nowShop = {};
var isShowShop = false;
var tmpDomObj={};
var flyer = $('<div class="shopCartFly"></div>'),shopDetailPageIscroll = null;
var nowProduct = {};
var firstMenuClick = false;
var productSwiper = null;
var productPicList = [];
var isNewLoading=true;
function showShop(shopId){

	console.log("showShop");

	pageLoadTips({showBg:false});

	// $('#pageShop').addClass('nowPage').show();
	// $('#pageShop').css('min-height','');
	// $('#pageShop').css('height',$(window).height());

    $('#pageShop').addClass('nowPage').show().siblings('.pageDiv').removeClass('nowPage').hide();
    $('#pageShop').css('min-height','');

    $('#pageShop').css('height',document.body.clientHeight);
    $('#shopPageShade').hide();

    nowPage = 'shop';
    is_refresh = false;

	if(isShowShop == false){	//默认加载，，，点击右上角搜索的时候，isShowShop就是 True了
		$('#shopContentBar').height(window_height-166);
		$('#shopContentBar>div').css({width:window_width,'max-width':640});
		
		$('#shopContentBar #shopProductBox').css('left','0px');
		$('#shopContentBar #shopReplyBox').css('left',window_width+'px');
		$('#shopContentBar #shopMerchantBox').css('left',window_width*2+'px');
		
		//$('#shopProductLeftBar2,#shopProductRightBar2,#shopProductBottomBar').css('height',window_height-166-50);
        //$('#shopProductRightBar2,#shopProductBottomBar').css('height',window_height);

		$('#shopCatBar .content').css('height',window_height-166-90);
		$('#shopMerchantBox,#shopReplyBox').css({height:window_height+50,'overflow-y':'auto'});

		//$('#shopProductRightBar2').width(window_width-100);
		//$('#shopProductLeftBar2,#shopProductRightBar2').css('height',window_height-166-50);
        //$('#shopProductRightBar2').css('height',window_height);
		//$('#shopProductRightBar2').width(window_width-100);

		if(motify.checkIos()){
			$('#shopProductLeftBar,#shopProductRightBar,#shopProductBottomBar,#shopMerchantBox,#shopReplyBox').css({'-webkit-overflow-scrolling':'touch'});
		}
		//点击子分类的事件
		$('#shopMenuBar li').click(function(){
			if(firstMenuClick == false){
				$('html,body').animate({scrollTop: $('#shopMenuBar').offset().top-50});
			}
			var tmpIndex = $(this).index();
			var tmpNav = $(this).data('nav');
			$(this).addClass('active').siblings().removeClass('active');
			pageLoadTips({showBg:false});
			$('#shopContentBar').animate({'margin-left':'-'+tmpIndex*window_width+'px'},function(){
                console.log("------------->showShopContent");
				showShopContent(tmpNav);
			});
		});

		$('#shopCatBar .title,#shopPageCatShade').click(function(){
			if($('#shopCatBar .title').hasClass('show')){
				$('#shopCatBar .title').removeClass('show');
				$('#shopCatBar .content').hide();
				$('#shopPageCatShade').hide();
			}else{
				$('#shopCatBar .title').addClass('show');
				$('#shopCatBar .content').show();
				$('#shopPageCatShade').show();
			}
		});
		$(document).on('click','#shopCatBar .content li',function(){

			$(this).addClass('active').siblings().removeClass('active');
			$('#shopCatBar .title').removeClass('show');
			$('#shopCatBar .content').hide();
			$('#shopPageCatShade').hide();
            $('#shopCatBar').hide();
            $('#shopCatBar').removeClass('sliderLeft');
			// alert('#shopProductBottomBar li.product_cat_'+$(this).data('cat_id'));
			$('#shopCatBar .title').html($(this).html());
			if($(this).data('cat_id') == '0'){
				$('#shopProductBottomBar li').show();
			}else{
				$('#shopProductBottomBar li').hide();
				$('#shopProductBottomBar li.product_cat_'+$(this).data('cat_id')).show();
                $('#shopProductLeftBar2 dl').scrollLeft($('#shopProductLeftBar2-'+$(this).data('cat_id')).offset().left - $('#shopProductLeftBar2 dl').offset().left+$('#shopProductLeftBar2 dl').scrollLeft());

                var top1=$('#shopProductRightBar2-'+$(this).data('cat_id')).offset().top;
                var h1=$('#shopHeader').height();
                var h2=$('#shopMenuBar').height();
                var h3=$('#shopProductLeftBar2').height();
                var hall=h1+h2+h3+15;
                $('html,body').animate({scrollTop: top1-hall});
                //$('#shopProductRightBar2').scrollTop($('#shopProductRightBar2-'+$(this).data('cat_id')).offset().top-$('#shopProductRightBar2').offset().top+$('#shopProductRightBar2').scrollTop());

            }
			$('#shopProductLeftBar2').css("top","101px");

		});
		
		$('#pageShop #backBtn').click(function(){
			goBackPage();
		});
		cartEventReg();

		$('#shopBanner').click(function(){
			$('#shopMenuBar li.merchant').trigger('click');
		});

        $('.shop_info').click(function () {
            $('#shopMenuBar li.merchant').trigger('click');
        });
		
		$(document).on('click','#shopProductLeftBar2 dd',function(){
            $(this).addClass('active').siblings().removeClass('active');
            // $('#shopProductRightBar2').scrollTop($('#shopProductRightBar2-'+$(this).data('cat_id')).offset().top-$('#shopProductRightBar2').offset().top+$('#shopProductRightBar2').scrollTop());
			//console.log($('#shopProductRightBar2').scrollTop());
			// //alert($(this).data('cat_id'));
			var top1=$('#shopProductRightBar2-'+$(this).data('cat_id')).offset().top;
			// var top2=$('#shopProductRightBar2').offset().top;
			// var top3=$('#shopProductRightBar2').scrollTop();
			var h1=$('#shopHeader').height();
            var h2=$('#shopMenuBar').height();
            var h3=$('#shopProductLeftBar2').height();
            var hall=h1+h2+h3+15;
            //top4=1000;
			// //alert(top4);
            // //$('html,body').animate({scrollTop: $('#shopProductRightBar2').offset().top-50});
            $('html,body').animate({scrollTop: top1-hall});
			//$('body').scrollTop(top4);
		});
		
		$(document).on('click','#shopProductRightBar2 li,#shopProductBottomBar li',function(event){
			//redirectPage(ajax_url_root+'classic_good&shop_id='+shopId+'&good_id='+$(this).data('product_id'));
            location.hash = 'good-'+shopId+'-'+$(this).data('product_id');
		});

		$(document).on('click','#shopSearchResult li',function (event) {
            location.hash = 'good-'+shopId+'-'+$(this).data('product_id');
        });

        $(document).on('click','#searchBtn',function(event){
            //redirectPage(ajax_url_root+'classic_good&shop_id='+shopId+'&good_id='+$(this).data('product_id'));
            location.hash = 'search';
        });

        $('#shopDetailpageClose').click(function(){
            //$('#shopDetailPage,#shopContentBar,#shopBanner').hide();
            //$('#shopTitle').empty();
            $('body').css('overflow-y','auto');
            $('#shopDetailPage').hide();
            $('#shopDetailPage').removeClass('sliderLeft');
            //alert(nowPage);
            if(nowPage == 'search') {
                location.hash = '';
            } else {
            	//alert(is_refresh+"---nowPage="+nowPage);
            	history.replaceState(null,'',location.pathname+location.search);
            	if(is_refresh){
                    window.location.href = storeUrl;
				}
            }
            nowPage="shop";	// by peter
        });

        $('#shopSearchpageClose').click(function(){
            //$('#shopTitle').empty();
            $('body').css('overflow-y','auto');
            $('#shopSearchPage').hide();
            $('#shopSearchPage').removeClass('sliderLeft');
            //location.hash = '';
            history.replaceState(null,'',location.pathname+location.search);
            if(is_refresh){
                window.location.href = storeUrl;
            }
            nowPage="shop";  // by peter
        });

        $("#search_btn").click(function () {
        	if($("#search_input").val() == ""){
        		alert("请输入关键词");
			}else {
        		pageLoadTips();
                $.getJSON(ajax_url_root + 'ajaxSearchGoods', {
                    store_id: shopId,
                    keyword: $("#search_input").val()
                }, function (result) {
                    if (result.is_result == 1) {
                        laytpl($('#shopProductRightBarTpl').html()).render(result.product_list, function (html) {
                            $('#shopSearchResult dl').html(html);
                        });
                        var nowShopCart = $.cookie('shop_cart_'+shopId);
                        if(nowShopCart){
                            nowShopCartArr = $.parseJSON(nowShopCart);
                            productCart=[];
                            if(nowShopCartArr.length > 0){
                                productCartNumber = productCartMoney = 0;
                                for(var i in nowShopCartArr){
                                    var tmpSpec = [];
                                    var tmpObj = nowShopCartArr[i].productParam;
                                    if(tmpObj.length > 0){
                                        for(var j in tmpObj){
                                            if(tmpObj[j].type == 'spec'){
                                                tmpSpec.push(tmpObj[j].id);
                                            }else if(tmpObj[j].type == 'side_dish'){
                                                // for(var k in tmpObj[j].data) {
                                                //     tmpSpec.push(tmpObj[j].data[k].dish_id);
                                                //     tmpSpec.push(tmpObj[j].data[k].dish_val_id);
                                                //     tmpSpec.push(tmpObj[j].data[k].dish_num);
                                                // }
                                                tmpSpec.push(tmpObj[j].dish_id);
                                            }else{
                                                for(var k in tmpObj[j].data){
                                                    tmpSpec.push(tmpObj[j].data[k].list_id);
                                                    tmpSpec.push(tmpObj[j].data[k].id);
                                                }
                                            }
                                        }
                                        if(tmpSpec.length > 0){
                                            var tmpSpecStr = nowShopCartArr[i].productId + '_' + tmpSpec.join('_');
                                            productCart[tmpSpecStr] = nowShopCartArr[i];
                                        }else{
                                            productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
                                        }
                                    }else{
                                        productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
                                        $('.product_'+nowShopCartArr[i].productId+' .plus').after('<div class="product_btn number productNum-'+nowShopCartArr[i].productId+'">'+nowShopCartArr[i].count+'</div>').after('<div class="product_btn min"></div>');
                                    }
                                    productCartNumber += nowShopCartArr[i].count;
                                    productCartMoney += nowShopCartArr[i].count * nowShopCartArr[i].productPrice;
                                }

                                //统计购物车功能
                                cartFunction('count');//1
                            }
                            // console.log(productCart);
                        }else{
                            cartFunction('count'); //2
                        }
                    } else {
                        var html = '<dd id="shopProductRightBar2-0" data-cat_id="0"><div class="cat_name">未搜索到结果</div></dd>'
                        $('#shopSearchResult dl').html(html);
                    }
                    pageLoadHides();
                });
            }
        });

		//SPEC radio 选择
        $(document).on('click','#shopDetailPageFormat li',function(event){
            $(this).addClass('active').siblings('li').removeClass('active');
            changeProductSpec();
        });

		//Dish radio选择
        $(document).on('click','#shopDetailPageDish li',function(event){
            //$(this).addClass('active').siblings('li').removeClass('active');
            //changeProductSpec();
			var min = $(this).data('min')
			var max = $(this).data('max');

            // if(max == 1){
            //     $(this).addClass('active').siblings('li').removeClass('active');
            // }else
			if(!$(this).hasClass('active')){
                var tmpActiveSize = $(this).closest('ul').find('.active').size();//当前的UL中已经选中的几个？
                //alert(tmpActiveSize);
                if(max != -1 && tmpActiveSize >= max){
                	if(max == 1 && tmpActiveSize==1){
                        $(this).closest('ul').find('.active').each(function () {
                            //if ($(this).hasClass('active')) {
                                $(this).removeClass('active');
                            //}
                        });
                        $(this).addClass('active').siblings('li').removeClass('active');
                     }else {
					//motify.log($(this).closest('ul').data('dish_name') + ' Options Maximum ' + max + '');
                    	motify.log('Please choose maximum ' + max + ' option(s) for ' + $(this).closest('ul').data('dish_name'));
                    }
                }else{
                    /* if(tmpActiveSize == maxSize-1){
                        motify.log('您最多能选择 '+maxSize+' 个，现在已经选择满了');
                    } */
                    $(this).addClass('active');
                }
            }else{
                $(this).removeClass('active');
            }
            changeProductSpec();
        });

		//Dish + 选择
        $(document).on('click','#shopDetailPageDish .product_btn.plus',function () {
        	var max = parseInt($(this).parent().data('max'));
            var min = parseInt($(this).parent().data('min'));
            var dish_id = $(this).parent().data('dish_id');
			var curr_all_num = 0;
            $(this).parents('#shopDetailPageDish_'+dish_id).find('.dish_memo').each(function () {
                curr_all_num += parseInt($(this).children('.number').html());
            });

            //alert($(this).parent().data('dish_name'));
            var curr_num = parseInt($(this).parent().children('.number').html());
			if(max == -1 || curr_all_num < max) {
                $(this).parent().children('.number').html(curr_num + 1);
                changeProductSpec();
            }else {
                //motify.log($(this).parent().data('dish_name') + ' Options Maximum ' + max + '');
                if (max==min){
                    motify.log('Please choose exactly ' + max + ' option(s) for ' + $(this).parent().data('dish_name'));
                }else{
                    motify.log('Please choose maximum ' + max + ' option(s) for ' + $(this).parent().data('dish_name'));
                }
            }
        });

		//Dish - 选择
        $(document).on('click','#shopDetailPageDish .product_btn.min',function () {
            var curr_num = parseInt($(this).parent().children('.number').html());
            if(curr_num > 0) {
                $(this).parent().children('.number').html(curr_num - 1);
                changeProductSpec();
            }
        });

		//Proper Radio选择
        $(document).on('click','#shopDetailPageLabel li',function(event){
            //var maxSize = $(this).closest('.row').data('num');
            var maxSize = $(this).data('num');
            if(maxSize == 1){
                $(this).addClass('active').siblings('li').removeClass('active');
            }else if(!$(this).hasClass('active')){
                var tmpActiveSize = $(this).closest('ul').find('.active').size();
                if(tmpActiveSize >= maxSize){
                    //motify.log($(this).closest('.row').data('label_name')+' Options Maximum '+maxSize+'');
                    motify.log('Please choose maximum ' + maxSize + ' option(s) for ' + $(this).closest('.row').data('label_name'));
                }else{
                    /* if(tmpActiveSize == maxSize-1){
                        motify.log('您最多能选择 '+maxSize+' 个，现在已经选择满了');
                    } */
                    $(this).addClass('active');
                }
            }else{
                $(this).removeClass('active');
            }
            changeProductSpec();
        });

        $(document).on('click','#shopDetailPageNumber .product_btn.plus,#shopDetailPageBuy',function(event){
            if(nowShop.store.is_close == 1 || nowShop.store.store_status=='0'){
                motify.log('Store closed');
                return false;
            }
            var intStock = parseInt($('#shopDetailPagePrice .stock_span').data('stock'));
            if(intStock != -1 && (intStock == 0 || intStock - parseInt($('#shopDetailPageNumber .number').html()) <= 0)){
                motify.log(getLangStr('_NO_STOCK_'));
                return false;
            }
            //验证属性是否选择  另一类 proper
			var is_no_select = false;
			$('#shopDetailPageLabelBox').find('.row').each(function () {
				var num = 0;
				var s_name = $(this).data('label_name');
				$(this).find('li').each(function () {
                    if ($(this).hasClass('active')) {
						num++;
                    }
                });
				if(num == 0){
                    motify.log('Please choose option(s) for '+ s_name);
                    is_no_select = true;
				}
            });

			//验证配送是否选择/// dish spec商品选项
            $.each($('#shopDetailPageDish .row'),function(i,item){
            	//alert("shopDetailPageDish");
            	var num = 0;
            	var min_num = $(item).data('min');
                var max_num = $(item).data('max');
                var dish_name = $(item).data('name');
                //var dish_val_name = $(item).data('dish_val_name');

                $.each($(item).find('li.active'),function(j,jtem){
                    num += 1;
                });
                $.each($(item).find('div.dish_memo'),function (j,jtem) {
                    var this_num = parseInt($(jtem).children('.number').html());
                    num += this_num;
                });
                if(num < min_num){
                    //motify.log(dish_name + ' selection(s) '+min_num+' required');
                    if(min_num==max_num){
                        motify.log('Please choose exactly '+min_num+' option(s) for '+ dish_name);
                    }else{
                        motify.log('Please choose minimum '+min_num+' option(s) for '+ dish_name);
					}
                    is_no_select = true;
                }
            });

			if(is_no_select){
				return false;
			}
            var obj 	= document.getElementById('cartInfo');
            var left_x = GetObjPos(obj)['x'];
            tmpDomObj = $(this);
            if(!(motify.checkApp() && motify.checkAndroid())){
                flyer.fly({
                    start: {
                        left: event.clientX-10,
                        top: event.clientY-10
                    },
                    end: {
                        left: left_x+20,
                        top: window_height-50,
                        width: 20,
                        height: 20
                    },
                    onEnd:function(){
                        cartFunction('plus',tmpDomObj,'productPage');
                        flyer.remove();
                    }
                });
            }else{
                cartFunction('plus',tmpDomObj,'productPage');
            }
            return false;
        });

        $(document).on('click','#shopDetailPageNumber .product_btn.min',function(event){
            tmpDomObj = $(this);
            cartFunction('min',tmpDomObj,'productPage');
            return false;
        });
		$(document).on('click','#shopProductRightBar2 .product_btn.plus,#shopProductBottomBar .product_btn.plus,#shopSearchResult .product_btn.plus',function(event){
			if(nowShop.store.is_close == 1 || nowShop.store.store_status=='0'){
				motify.log(getLangStr('_SHOP_AT_REST_'));
				return false;
			}
			tmpDomObj = $(this);
			var intStock = parseInt(tmpDomObj.closest('li').data('stock'));
			if(intStock != -1 && (intStock == 0 || intStock - parseInt(tmpDomObj.siblings('.number').html()) <= 0)){
				motify.log(getLangStr('_NO_STOCK_'));
				return false;
			}
            var obj 	= document.getElementById('cartInfo');
            var left_x = GetObjPos(obj)['x'];
			if(!(motify.checkApp() && motify.checkAndroid())){
				flyer.fly({
					start: {
                        left: event.clientX-10,
                        top: event.clientY-10
					},
					end: {
						left: left_x+20,
						top: window_height-50,
						width: 20,
						height: 20
					},
					onEnd:function(){
						cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));
						flyer.remove();
					}
				});
			}else{
				cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));
			}
			return false;
		});
		$(document).on('click','#shopProductRightBar2 .bgPlusBack,#shopSearchResult .bgPlusBack',function(event){
			if(nowShop.store.is_close == 1 || nowShop.store.store_status=='0'){
				motify.log(getLangStr('_SHOP_AT_REST_'));
				return false;
			}
			tmpDomObj = $(this);

			var intStock = parseInt(tmpDomObj.closest('li').data('stock'));
			if(intStock != -1 && (intStock == 0 || intStock - parseInt(tmpDomObj.siblings('.number').html()) <= 0)){
				motify.log(getLangStr('_NO_STOCK_'));
				return false;
			}

            var obj 	= document.getElementById('cartInfo');
            var left_x = GetObjPos(obj)['x'];

			console.log("event.clientX="+event.clientX+'--- event.clientY='+event.clientY);
			if(!(motify.checkApp() && motify.checkAndroid())){
				flyer.fly({
					start: {
						left:event.clientX-10,
						top: event.clientY-10
					},
					end: {
						left: left_x+20,
						top: window_height-50,
						width: 20,
						height: 20
					},
					onEnd:function(){
						// alert(111122);
						cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));
						flyer.remove();
					}
				});
			}else{
				cartFunction('plus',tmpDomObj,tmpDomObj.closest('li'));
			}
			return false;
		});
		$(document).on('click','#shopProductRightBar2 .bgMinBack,#shopSearchResult .bgMinBack',function(event){
			tmpDomObj = $(this).siblings('.product_btn.min');
			cartFunction('min',tmpDomObj,tmpDomObj.hasClass('cart') ? tmpDomObj.closest('dd') : tmpDomObj.closest('li'));
			return false;
		});
		$(document).on('click','#shopProductRightBar2 .product_btn.min,#shopSearchResult .product_btn.min',function(event){
			tmpDomObj = $(this);
			cartFunction('min',tmpDomObj,tmpDomObj.hasClass('cart') ? tmpDomObj.closest('dd') : tmpDomObj.closest('li'));
			return false;
		});
		
		$('#shopReplyBox ul li').click(function(){
			if($(this).hasClass('active')){
				return false;
			}
			$(this).addClass('active').siblings().removeClass('active');
			
			$('#shopReplyBox dl').empty();
			$('#showMoreReply').hide();
			pageLoadTips({showBg:false});
			$.post(shopReplyUrl+nowShop.store.id,{tab:$(this).data('tab')},function(result){
				result = $.parseJSON(result);	
				laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
					$('#shopReplyBox dl').html(html);
				});
				$('#showMoreReply').data('page','2');
				if(result.total > result.now){
					$('#showMoreReply').show();
				}else{
					$('#showMoreReply').hide();
				}
				pageLoadHides();
			});
		});
		
		$('#showMoreReply').click(function(){
			pageLoadTips({showBg:false});
			var nowPage = parseInt($(this).data('page'));
			$.post(shopReplyUrl+nowShop.store.id,{tab:$('#shopReplyBox ul li.active').data('tab'),page:nowPage},function(result){
				result = $.parseJSON(result);	
				laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
					$('#shopReplyBox dl').append(html);
				});

				$('#showMoreReply').data('page',(nowPage+1));
				
				if(result.total > result.now){
					$('#showMoreReply').show();
				}else{
					$('#showMoreReply').hide();
				}
				
				pageLoadHides();
			});
		});
		
		/*Right滚动条*/
		//if(motify.checkIos()){
            //console.log("ios and android");

		$('#pageShop').on('touchmove',function(){
			scrollProductEvent('ios');
		});

		$('#pageShop').scroll(function(){
            var top = $(document).scrollTop();
			$('#pageShop').trigger('touchmove');
		});
			// $('#shopProductRightBar2,#shopProductBottomBar').on('touchmove',function(){
			// 	scrollProductEvent('ios');
			// });
			// $('#shopProductRightBar2,#shopProductBottomBar').scroll(function(){
			// 	$('#shopProductRightBar2').trigger('touchmove');
			// });
		// }else{
         //    console.log("android");
         //    $('#container').scroll(function(){
         //        scrollProductEvent('android');
         //    });
		// 	// $('#shopProductRightBar2,#shopProductBottomBar').scroll(function(){
		// 	// 	scrollProductEvent('android');
		// 	// });
		// }

		isShowShop = true;
	}
	
	if(nowShop.store && shopId == nowShop.store.id){
        //$('#shopHeader').css('background','rgba(255,255,255,0)');
		$('#shopTitle').html(nowShop.store.name);
        $('#shopTitle_Header').html(nowShop.store.name);

		firstMenuClick = true;
		// $('#shopMenuBar .product').trigger('click');
		// showShopContent('product');
		pageLoadHides();
		changeWechatShare('shop',{title:nowShop.store.name,desc:nowShop.store.txt_info,imgUrl:nowShop.store.image,link:shopShareUrl+nowShop.store.id});
	}else{

		productCart=[];
		productCartNumber = 0;
		productCartMoney  = 0;
		$('#shopProductCart #cartNumber').html(productCartNumber);
		$('#shopProductCart #cartMoney').html(productCartMoney.toFixed(2));
		$('#shopProductCart #cartInfo').hide();
		$('#shopProductCart #emptyCart').show();
		$('#shopProductLeftBar2 dl,#shopProductRightBar2 dl').empty();
		$('#shopProductBottomBar ul,#shopCatBar .content ul').empty();

		$.getJSON(ajax_url_root+'ajaxShop',{store_id:shopId},function(result){
			//console.log(result);
			$('#shopTitle').html(result.store.name);
            $('#shopTitle_Header').css("opacity","0");
            $('#shopTitle_Header').html(result.store.name);

            $('#stars_text').html(result.store.star);

            $('#background_area').css('background-image','url('+result.store.image+')');
			if(result.store.delivery){
                $('#deliveryText').html(getLangStr('_DELI_PRICE_') +' $ '+result.store.delivery_money+' | '+ getLangStr('_PACK_PRICE_') +' '+ result.store.pack_fee);//+ ' | ' + getLangStr('_DEIL_NUM_MIN_',result.store.delivery_time)
			}else{
                $('#deliveryText').html(getLangStr('_ONLY_SELF_'));
			}
			$('#shopNoticeText').html(result.store.keywords);
			// $('#shopCouponText').html(parseCoupon(result.store.coupon_list,'text')+';'+result.store.store_notice);
			$('#shopCouponText').html(parseCoupon(result.store.coupon_list,'text'));
			if(result.store.is_close == 1 || result.store.store_status=='0'){
                $('#shopProductCart').attr("data-close","1");
                $('#checkCartEmpty').html(getLangStr('_SHOP_AT_REST_'));
                $('#emptyCart').html("");
               //$('.is_close').html('CLOSED');
                //$('.is_close').addClass('close_s');
			}else if(result.store.delivery){
                $('#shopProductCart').attr("data-close","0");
                //$('#checkCartEmpty').html(getLangStr('_NUM_DELI_PRICE_',result.store.delivery_price.toFixed(2)));
                $('#checkCartEmpty').html("");
               // $('.is_close').html('OPEN');
			}
			var reduce_html = '';
			if(result.store.reduce) {
                for (var i = 0; i < result.store.reduce.length; ++i) {
                    reduce_html += '<span>' + result.store.reduce[i] + '</span>';
                }
                $('.star').html(reduce_html);
            }

			nowShop = result;
			
			$('#shopProductBox,#shopMerchantBox,#shopReplyBox').data('isShow','0');
			$('#shopReplyBox').hide();
			// showShopContent('product');
			firstMenuClick = true;

			//------------------------------- SHIT-----------------------------------
			$('#shopMenuBar .product').trigger('click');
			//--------------------------------------------------------------------------------------

			changeWechatShare('shop',{title:nowShop.store.name,desc:nowShop.store.txt_info,imgUrl:nowShop.store.image,link:shopShareUrl+nowShop.store.id});
            //如果店铺关闭，要提示
            var shop_remind = ""
			if(nowShop.store.is_close == '1' || nowShop.store.store_status=='0'){
                shop_remind = "This store is currently closed, and delivery is unavailable at the moment.";
			}else{
                if(nowShop.store.shop_remind != '') {
                    // var shop_remind = nowShop.store.shop_remind.replace(/\r\n/g, "<br>");
                    // shop_remind = shop_remind.replace(/\s/g, "&nbsp;");
                    var remind = nowShop.store.shop_remind.split('\n');
                    for(var str of remind){
                       shop_remind += '<p>'+str+'</p>';
                    }
                    //nowShop.store.shop_remind.replace('\n','<br/>');
                }
			}
			pageLoadHides();
			//console.log("nowShop.store.shop_remind="+nowShop.store.shop_remind);
            if(shop_remind!= '') {
                var remindTipLayer = layer.open({
                    content: shop_remind,
                    btn: ['Confirm'],
                    end: function () {
                        layer.close(remindTipLayer);
                    }
                });
            }
		});
	}

	$('#shopContentBar,#shopBanner').show();

	$('.sub_right').click(function () {
		showStoreCategory();
    });
	// setTimeout(function(){
		// pageLoadHides();
	// },1500);
}

function scrollProductEvent(phoneType){
    //var scrollRightTop = $('#shopMenuBar').css('display') == 'none' ? $('#shopProductBottomBar').scrollTop() :$('#shopProductRightBar2').scrollTop();
    //$('#pageShop').css('height',document.body.clientHeight+200);
    //$('#shopProductRightBar2').css('height',document.body.clientHeight-200);
    var top = $(document).scrollTop();
    //console.log("top="+top);
    var scrollRightTop =document.documentElement.scrollTop;
    //var shopMenuBarTop=document.getElementById("shopMenuBar").clientY;
    $('#debug').html("top--"+scrollRightTop);
    //console.log(scrollRightTop);
    $('#shopMenuBar').css("max-width","640px");
    if(scrollRightTop >=200){ // 已经折叠好，可以翻滚商品了
        //console.log(200);
        //if (scrollRightTop>200) document.documentElement.scrollTop=200;
        $('#shopMenuBar_Space').css("height","40px");
        $('#shopMenuBar').css("position","fixed");
        //$('#shopMenuBar').css("max-width","640px");
        $('#shopProductRightBar2').css("overflow-y","auto");
        $('#shopBanner').css("opacity","0");
        $('#shopHeader').css('background','rgba(255,255,255,'+ 1 +')')
        $('#shopTitle_Header').css("opacity","1");
        $('#shopTitle_Header').css("margin-top",5)
        //$(window).scrollTop($('#shopMenuBar').css('display') == 'none' ? $('#shopCatBar').offset().top-50 : $('#shopMenuBar').offset().top-50);
    }else{		//正在打开
        //console.log(1);
        $('#shopMenuBar_Space').css("height","0px");
        $('#shopMenuBar').css("position","sticky");
        //$('#shopMenuBar').css("max-width","");
        $('#shopProductRightBar2').css("overflow-y","hidden");
        $('#shopBanner').css("opacity",1-(scrollRightTop/200));
        var start_fade_offset=160;
        if (scrollRightTop>start_fade_offset){
            $('#shopTitle_Header').css("opacity",(scrollRightTop-start_fade_offset)/(200-start_fade_offset));
            $('#shopTitle_Header').css("margin-top",5+15*(1-(scrollRightTop-start_fade_offset)/(200-start_fade_offset)));

        }else{
            $('#shopTitle_Header').css("opacity","0");
            $('#shopTitle_Header').css("margin-top",20)
        }

        $('#shopHeader').css('background','rgba(255,255,255,'+ scrollRightTop/200 +')')
    }
}

function scrollProduct2Event(phoneType){
    //var scrollRightTop = $('#shopMenuBar').css('display') == 'none' ? $('#shopProductBottomBar').scrollTop() :$('#shopProductRightBar2').scrollTop();
    //$('#pageShop').css('height',document.body.clientHeight+200);
    //$('#shopProductRightBar2').css('height',document.body.clientHeight-200);
    //var top = $(document).scrollTop();
    //console.log("top="+top);
    //var scrollRightTop =document.documentElement.scrollTop;

    var shopProductRightBar2Top=document.getElementById("shopProductRightBar2").scrollTop;
    $('#debug2').html("shopProductRightBar2Top--"+shopProductRightBar2Top);
    //console.log(scrollRightTop);
    //
    // if(scrollRightTop >=190){ // 已经折叠
    //     console.log(200);
    //     //if (scrollRightTop>200) document.documentElement.scrollTop=200;
    //     $('#shopProductRightBar2').css("overflow-y","auto");
    //     $('#shopBanner').css("opacity","0");
    //     $('#shopHeader').css('background','rgba(255,255,255,'+ 1 +')')
    //     $('#shopTitle_Header').css("opacity","1");
    //     $('#shopTitle_Header').css("margin-top",5)
    //     //$(window).scrollTop($('#shopMenuBar').css('display') == 'none' ? $('#shopCatBar').offset().top-50 : $('#shopMenuBar').offset().top-50);
    // }else{		//正在打开
    //     console.log(1);
    //     $('#shopProductRightBar2').css("overflow-y","hidden");
    //     $('#shopBanner').css("opacity",1-(scrollRightTop/200));
    //     var start_fade_offset=160;
    //     if (scrollRightTop>start_fade_offset){
    //         $('#shopTitle_Header').css("opacity",(scrollRightTop-start_fade_offset)/(200-start_fade_offset));
    //         $('#shopTitle_Header').css("margin-top",5+15*(1-(scrollRightTop-start_fade_offset)/(200-start_fade_offset)));
    //
    //     }else{
    //         $('#shopTitle_Header').css("opacity","0");
    //         $('#shopTitle_Header').css("margin-top",20)
    //     }
    //
    //     $('#shopHeader').css('background','rgba(255,255,255,'+ scrollRightTop/200 +')')
    // }
}

function changeProductSpec(){
	$('#shopDetailPageNumber .number').html('0');
	var curr_price = nowProduct.price;
	if(nowProduct.spec_list){
		var productSpecId = [];
		$.each($('#shopDetailPageFormat .row'),function(i,item){
			productSpecId.push($(item).find('li.active').data('spec_list_id'));
		});
		var productSpecStr = productSpecId.join('_');
		var nowProductSpect = nowProduct.list[productSpecStr];
        curr_price = nowProductSpect.price;
		//$('#shopDetailPagePrice').html('$'+nowProductSpect.price+'<span class="unit"><em>/ </em>'+nowProduct.unit+'</span>'+(nowProductSpect.stock_num != -1 ? '<span class=\'stock_span\' data-stock="'+nowProductSpect.stock_num+'">Stock:'+nowProductSpect.stock_num+'</span>' : '<span data-stock="-1"></span>') + (nowProduct.deposit_price > 0 ? '<span>(Deposit:$'+ nowProduct.deposit_price +')</span>' : ''));
		
		if(nowProduct.properties_list){
			for(var i in nowProductSpect.properties){
				$('.productProperties_'+nowProductSpect.properties[i].id).data('num',nowProductSpect.properties[i].num);
			}
		}
		var nowProductCartLabel = nowProduct.goods_id + '_' + productSpecStr;
	}else{
		var nowProductCartLabel = nowProduct.goods_id;
	}
	if(nowProduct.properties_list){
		$.each($('#shopDetailPageLabelBox .row'),function(i,item){
			var tmpProductProperties = [];
			$.each($(item).find('li.active'),function(j,jtem){
				nowProductCartLabel = nowProductCartLabel+'_'+$(jtem).data('label_list_id')+'_'+$(jtem).data('label_id');
			});
		});
	}
	if(nowProduct.side_dish){
		var productDish = [];
        $.each($('#shopDetailPageDish .row'),function(i,item){
            $.each($(item).find('li.active'),function(j,jtem){
                productDish.push($(jtem).data('dish_id')+'_'+$(jtem).data('dish_val_id'));
                curr_price += parseFloat($(jtem).data('dish_price'));
                nowProductCartLabel = nowProductCartLabel+'_'+$(jtem).data('dish_id')+'_'+$(jtem).data('dish_val_id')+'_1';
            });
            $.each($(item).find('div.dish_memo'),function (j,jtem) {
                var this_num = parseInt($(jtem).children('.number').html());
                var name_div = $(this).parent('div').find('.dish_name');
                if(this_num > 0) {
                	$(name_div).css("color","#ffa52d");
                    productDish.push($(jtem).data('dish_id') + '_' + $(jtem).data('dish_val_id') + '_' + this_num);
                    curr_price += parseFloat($(jtem).data('dish_price')) * this_num;
                    nowProductCartLabel = nowProductCartLabel+'_'+$(jtem).data('dish_id')+'_'+$(jtem).data('dish_val_id')+ '_' + this_num;
                }else{
                    $(name_div).css("color","#000000");
				}
            })
        });
	}

	curr_price = curr_price.toFixed(2);

	if(typeof (nowProductSpect) != 'undefined')
    	$('#shopDetailPagePrice').html('$'+curr_price+'<span class="unit"><em>/ </em>'+nowProduct.unit+'</span>'+(nowProductSpect.stock_num != -1 ? '<span class=\'stock_span\' data-stock="'+nowProductSpect.stock_num+'">Stock:'+nowProductSpect.stock_num+'</span>' : '<span data-stock="-1"></span>') + (nowProduct.deposit_price > 0 ? '<span>(Deposit:$'+ nowProduct.deposit_price +')</span>' : ''));
	else
        $('#shopDetailPagePrice').html('$'+curr_price+'<span class="unit"><em>/ </em>'+nowProduct.unit+'</span>'+(nowProduct.stock_num != -1 ? '<span class=\'stock_span\' data-stock="'+nowProduct.stock_num+'">Stock:'+nowProduct.stock_num+'</span>' : '<span data-stock="-1"></span>') + (nowProduct.deposit_price > 0 ? '<span>(Deposit:$'+ nowProduct.deposit_price +')</span>' : ''));

	$('#shopDetailPageNumber .number').attr('class','product_btn number');
	$('#shopDetailPageNumber .number').addClass('productNum-'+nowProductCartLabel);

	if(productCart[nowProductCartLabel]){
		$('#shopDetailPageNumber').show();
		$('#shopDetailPageNumber .number').html(productCart[nowProductCartLabel].count);
		$('#shopDetailPageBuy').hide();
	}else{
		$('#shopDetailPageNumber').hide();
		$('#shopDetailPageBuy').show();
	}
}

var productCart = [],productCartNumber = 0,productCartMoney=0;
function cartFunction(type,obj,dataObj){
    console.log("cartFunction->"+type);
    if ($('#shopProductCart').data('close')=="0") {
		if(dataObj == 'productPage'){
			console.log("cartFunction->productPage");
			var productId = nowProduct.goods_id;
			var productName = nowProduct.name;
			if(nowProduct.spec_list){
				var productSpecListId = [],productSpecId = [],productSpecText = [];
				$.each($('#shopDetailPageFormat .row'),function(i,item){
					productSpecListId.push($(item).find('li.active').data('spec_list_id'));
					productSpecId.push($(item).find('li.active').data('spec_id'));
					productSpecText.push($(item).find('li.active').html());
				});
				var productSpecStr = productSpecListId.join('_');

				var productKey = productId + '_' + productSpecStr;
				var nowProductSpect = nowProduct.list[productSpecStr];
				var productPrice = parseFloat(nowProductSpect.price);
				var productStock = nowProductSpect.stock_num;

				var productParam = [];
				for(var i in productSpecListId){
					productParam.push({'type':'spec','spec_id':productSpecId[i],'id':productSpecListId[i],'name':productSpecText[i]});
				}
			}else{
				var productKey = productId;
				var productPrice = nowProduct.price;
				var productParam = [];
				var productStock = nowProduct.stock_num;
			}
			if(nowProduct.properties_list){
				$.each($('#shopDetailPageLabelBox .row'),function(i,item){
					var tmpProductProperties = [];
					$.each($(item).find('li.active'),function(j,jtem){
						productKey = productKey+'_'+$(jtem).data('label_list_id')+'_'+$(jtem).data('label_id');
						tmpProductProperties.push({'id':$(jtem).data('label_id'),'list_id':$(jtem).data('label_list_id'),'name':$(jtem).html()});
					});
					productParam.push({'type':'properties','data':tmpProductProperties});
				});
			}
			if(nowProduct.side_dish){
				var tmpProductDish = [];
				$.each($('#shopDetailPageDish .row'),function(i,item){
					var dish_id = "";
					var dish_name = "";
					$.each($(item).find('li.active'),function(j,jtem){
						productKey = productKey+'_'+$(jtem).data('dish_id')+'_'+$(jtem).data('dish_val_id')+'_1';
						//tmpProductDish.push({'dish_id':$(jtem).data('dish_id'),'dish_val_id':$(jtem).data('dish_val_id'),'dish_num':1,'dish_name':$(jtem).data('dish_name'),'dish_val_name':$(jtem).data('dish_val_name'),'dish_price':$(jtem).data('dish_price')});
						var new_dish = $(jtem).data('dish_id')+","+$(jtem).data('dish_val_id')+",1,"+$(jtem).data('dish_price');
						if(dish_id == "")
							dish_id = new_dish;
						else
							dish_id += "|" + new_dish;
						dish_name += dish_name == "" ? $(jtem).data('dish_val_name') : ";" + $(jtem).data('dish_val_name');
						productPrice += parseFloat($(jtem).data('dish_price'));
					});
					$.each($(item).find('div.dish_memo'),function (j,jtem) {
						var this_num = parseInt($(jtem).children('.number').html());
						if(this_num > 0) {
							productKey = productKey+'_'+$(jtem).data('dish_id')+'_'+$(jtem).data('dish_val_id')+'_'+this_num;
							//tmpProductDish.push({'dish_id':$(jtem).data('dish_id'),'dish_val_id':$(jtem).data('dish_val_id'),'dish_num':this_num,'dish_name':$(jtem).data('dish_name'),'dish_val_name':$(jtem).data('dish_val_name'),'dish_price':$(jtem).data('dish_price')});
							var new_dish = $(jtem).data('dish_id')+","+$(jtem).data('dish_val_id')+","+this_num+","+$(jtem).data('dish_price');
							if(dish_id == "")
								dish_id = new_dish;
							else
								dish_id += "|" + new_dish;

							var c_name = "";
							if(this_num > 1){
								c_name = $(jtem).data('dish_val_name')+"*"+this_num;
							}else{
								c_name = $(jtem).data('dish_val_name');
							}

							dish_name += dish_name == "" ? c_name : ";" + c_name;
							productPrice += parseFloat($(jtem).data('dish_price')) * this_num;
						}
					});
					//productParam.push({'type':'side_dish','data':tmpProductDish});
					productParam.push({'type':'side_dish','dish_id':dish_id,'dish_name':dish_name});
				});
			}
		}else if(type != 'count'){
			console.log("cartFunction->count");
			if(dataObj.hasClass('cartDD') && dataObj.find('.cartLeft').hasClass('hasSpec')){
				var productKey = dataObj.find('.spec').data('product_id');
				var productStock = dataObj.find('.spec').data('stock');
			}else{
				var productKey = dataObj.data('product_id');
				var productStock = dataObj.data('stock');
			}
			var productId = dataObj.data('product_id');
			var productName = dataObj.data('product_name');
			var productPrice = parseFloat(dataObj.data('product_price'));
			var productParam = [];
		}

		if(type == 'plus'){
			if(dataObj != 'productPage' && dataObj.hasClass('cartDD')){
				var tmpStock = parseInt(dataObj.data('stock'));
				if(tmpStock != -1 && productCart[productKey] && productCart[productKey]['count'] >= tmpStock){
					motify.log(getLangStr('_NO_STOCK_'));
					return false;
				}
			}
			$('#shopProductCart .cart').addClass('bound');
			setTimeout(function(){
				$('#shopProductCart .cart').removeClass('bound');
			},500);
			if(productCart[productKey]){
				productCart[productKey]['count']++;
				$('.productNum-'+productKey).html(productCart[productKey]['count']);
			}else{
				if(dataObj == 'productPage'){
					$('#shopDetailPageBuy').hide();
					$('#shopDetailPageNumber').show();
					$('#shopDetailPageNumber .number').html('1');

					$('.product_'+productId+' .plus').after('<div class="product_btn number productNum-'+productId+'">1</div>').after('<div class="product_btn min"></div>');

				}else{
					obj.after('<div class="product_btn number productNum-'+productId+'">1</div>');
					obj.after('<div class="product_btn min"></div>');
				}
				productCart[productKey] = {
					'productId':productId,
					'productName':productName,
					'productPrice':productPrice,
					'productStock':productStock,
					'productParam':productParam,
					'count':1,
				};
			}
			productCartNumber++;
			productCartMoney = productCartMoney+productPrice;
		}else if(type == 'min'){
			$('#shopProductCart .cart').addClass('bound');
			setTimeout(function(){
				$('#shopProductCart .cart').removeClass('bound');
			},500);
			if(productCart[productKey].count == 1){
				if(dataObj == 'productPage'){
					$('#shopDetailPageBuy').show();
					$('#shopDetailPageNumber').hide();
					$('#shopDetailPageNumber .number').html('0');
				}else{
					obj.siblings('.number').remove();
					obj.remove();
					if(dataObj.hasClass('cartDD')){
						dataObj.remove();
						$('#shopProductRightBar2 .productNum-'+productKey).siblings('.min').remove();
						$('#shopProductRightBar2 .productNum-'+productKey).remove();
						$('#shopProductBottomBar .productNum-'+productKey).siblings('.min').remove();
						$('#shopProductBottomBar .productNum-'+productKey).remove();
						$('#shopDetailPageBuy').show();
						$('#shopDetailPageNumber').hide();
						$('#shopDetailPageNumber .number').html('0');
					}
				}
				delete productCart[productKey];
			}else{
				productCart[productKey]['count']--;
				$('.productNum-'+productKey).html(productCart[productKey]['count']);
			}
			productCartNumber--;
			productCartMoney = productCartMoney - productPrice;
		}


		$('#shopProductCart #cartNumber').html(productCartNumber);
		$('#shopProductCart #cartMoney').html(productCartMoney.toFixed(2));


		if(productCartNumber == 0){
			//$('#checkCartEmpty').removeClass('noEmpty').show().html(getLangStr('_NUM_DELI_PRICE_',(nowShop.store.delivery_price).toFixed(2)));
			$('#checkCartEmpty').removeClass('noEmpty').show().html();
			$('#checkCart').removeClass('noEmpty').hide();

		}else if(nowShop.store.delivery == true && parseFloat(productCartMoney.toFixed(2)) < nowShop.store.delivery_price){
			$('#checkCart').hide();
			//$('#checkCartEmpty').addClass('noEmpty').show().html(getLangStr('_POOR_DELI_') + getLangStr('_NUM_DELI_PRICE_',(nowShop.store.delivery_price - parseFloat(productCartMoney.toFixed(2))).toFixed(2)));
			$('#checkCartEmpty').addClass('noEmpty').show().html();
		}else{
			$('#checkCartEmpty').hide();
			$('#checkCart').show();
			if(nowShop.store.free_delivery == 1){
				if(nowShop.store.event.use_price - productCartMoney.toFixed(2) <= 0){
					$('#free_delivery').html("Enjoy <label style='color: #ffa52d'>Free</label> delivery!");
				}else{
					var cha = (nowShop.store.event.use_price - productCartMoney.toFixed(2)).toFixed(2);
					$('#free_delivery').html("$" + (cha) + " to <label style='color: #ffa52d'>Free</label> delivery!");
				}
			}
		}
		console.log("close="+$('#shopProductCart').data('close'));

        if (productCartNumber > 0) {
            $('#shopProductCart #emptyCart').hide();
            $('#shopProductCart #cartInfo').show();
        } else {
            if ($('#cartInfo').hasClass('isShow')) {
                $('#shopProductCartShade').trigger('click');
            }
            $('#shopProductCart #cartInfo').hide();
            $('#shopProductCart #emptyCart').show();
        }
        // console.log(productCart);
        stringifyCart();
    }
	// console.log($.cookie('shop_cart_'+nowShop.store.id));

	if(nowPage == 'search' || nowPage == 'goods'){
		is_refresh = true;
	}
}

function stringifyCart(){
	var cookieProductCart = [];
	for(var i in productCart){
		cookieProductCart.push(productCart[i]);
	}
	$.cookie('shop_cart_'+nowShop.store.id,JSON.stringify(cookieProductCart),{expires:700,path:'/'});
}

function parseCart(){
	
}

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
//二级导航
function showShopContent(nav){
	console.log("showShopContent-->"+nav);

	if(nav == 'product'){						//商品加载

        $('#shopMerchantBox').hide();
        $('#shopReplyBox').hide();

        $('#shopProductBox').show();
        $('#shopProductBox').show();

        if (isNewLoading){
            console.log("isNewLoading-->"+isNewLoading);
            isNewLoading=false;
		}else{
            console.log("isNewLoading-->"+isNewLoading);
            $(document).scrollTop(200);
        }

		if(nowShop.store.tmpl == '0'){

			$('#shopCatBar,#shopProductBottomBar').hide();
			$('#shopMenuBar').fadeIn('slow')
			$('#shopProductLeftBar2,#shopProductRightBar2').show();	//商品分类

		}else if(nowShop.store.tmpl == '1'){

			$('#shopMenuBar,#shopProductLeftBar2,#shopProductRightBar2').hide();
			$('#shopProductBottomBar').show();
			$('#shopCatBar').fadeIn('slow').find('.title').html('全部分类');

		}

		if($('#shopProductBox').data('isShow') != '1'){
			console.log("#shopProductBox').data('isShow') != '1'");
			$('#shopProductLeftBar2 dl,#shopProductRightBar2 dl').empty();
			storeTheme = nowShop.store.store_theme;
			if (nowShop.product_list) {
				if(nowShop.store.tmpl == '0'){
                    console.log("nowShop.store.tmpl == '0'");
					laytpl($('#shopProductLeftBarTpl').html()).render(nowShop.sort_list, function(html){

						$('#shopProductLeftBar2 dl').html(html);

                        base_width = $('#shopProductLeftBar2').find('dl').width();
                        $('#shopProductLeftBar2').find('dd').each(function () {
                            act_width += $(this).width()+parseInt($(this).css('padding-left'))*2;
                        });
                        setSubMove();

						$("#shopProductLeftBar2 dd span").click(function(){
							$(this).parents("dd").addClass("active").siblings("dd").removeClass("active");
							$(this).siblings("ul").find("li").removeClass("active").find(".p").hide();
							//showGoodsBySortId($(this).data('sort_id'), nowShop.store.id);
						});
						$("#shopProductLeftBar2 dd li em").click(function(){
							$(this).parents("li").siblings("li").removeClass("active").find(".p").hide();
							$(this).parents("li").addClass("active").find(".p").show().find("p").removeClass("active");
							showGoodsBySortId($(this).data('sort_id'), nowShop.store.id);
						});
						$("#shopProductLeftBar2 dd li p").click(function(){
							$(this).addClass("active").siblings("p").removeClass("active");
							showGoodsBySortId($(this).data('sort_id'), nowShop.store.id);
						});

					});
					laytpl($('#shopProductRightBarTpl').html()).render(nowShop.product_list, function(html){
						$('#shopProductRightBar2 dl').html(html+"<br/><br/><br/>");
					});
                    laytpl($('#shopProductTopBarTpl').html()).render(nowShop.product_list, function(html){
                        $('#shopCatBar .content ul').html(html);
                    });
				} else if (nowShop.store.tmpl == '1') {
                    console.log("nowShop.store.tmpl == '1'");
					laytpl($('#shopProductTopBarTpl').html()).render(nowShop.product_list, function(html){
						$('#shopCatBar .content ul').html(html);
					});
					laytpl($('#shopProductBottomBarTpl').html()).render(nowShop.product_list, function(html){
						$('#shopProductBottomBar ul').html(html);
					});
					$('#shopProductBottomBar .position_img').height($('#shopProductBottomBar .position_img:eq(0)').width());
					// $('#shopProductBottomBar li').css('margin-top',window_width*0.02);
					$('#shopProductBottomBar ul').css('margin-bottom',window_width*0.02);
				}
			}
			var nowShopCart = $.cookie('shop_cart_'+nowShop.store.id);
			if(nowShopCart){
				nowShopCartArr = $.parseJSON(nowShopCart);
				productCart=[];
				if(nowShopCartArr.length > 0){
					productCartNumber = productCartMoney = 0;
					for(var i in nowShopCartArr){
						var tmpSpec = [];
						var tmpObj = nowShopCartArr[i].productParam;
						if(tmpObj.length > 0){
							for(var j in tmpObj){
								if(tmpObj[j].type == 'spec'){
									tmpSpec.push(tmpObj[j].id);
								}else if(tmpObj[j].type == 'side_dish'){
                                    // for(var k in tmpObj[j].data) {
                                    //     tmpSpec.push(tmpObj[j].data[k].dish_id);
                                    //     tmpSpec.push(tmpObj[j].data[k].dish_val_id);
                                    //     tmpSpec.push(tmpObj[j].data[k].dish_num);
                                    // }
									tmpSpec.push(tmpObj[j].dish_id);
                                }else{
									for(var k in tmpObj[j].data){
										tmpSpec.push(tmpObj[j].data[k].list_id);
										tmpSpec.push(tmpObj[j].data[k].id);
									}
								}
							}
							if(tmpSpec.length > 0){
								var tmpSpecStr = nowShopCartArr[i].productId + '_' + tmpSpec.join('_');
								productCart[tmpSpecStr] = nowShopCartArr[i];
							}else{
								productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
							}
						}else{
							productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
							$('.product_'+nowShopCartArr[i].productId+' .plus').after('<div class="product_btn number productNum-'+nowShopCartArr[i].productId+'">'+nowShopCartArr[i].count+'</div>').after('<div class="product_btn min"></div>');
						}
						productCartNumber += nowShopCartArr[i].count;
						productCartMoney += nowShopCartArr[i].count * nowShopCartArr[i].productPrice;
					}
					
					//统计购物车功能
					cartFunction('count');//3!!!!!!!!!!!!!!!! 页面加载后，如果购物车有商品就会先刷新这个
				}
				// console.log(productCart);
			}else{
				cartFunction('count');//4
			}
			$('#shopProductBox').data('isShow','1');
		}
		pageLoadHides();
	}else if(nav == 'merchant'){ //商铺信息

        $('#shopReplyBox').hide();
		$('#shopCatBar').hide();
		$('#shopMenuBar').show();
        $('#shopProductBox').hide();
        $('#shopMerchantBox').show();
        $(document).scrollTop(200);

		if($('#shopMerchantBox').data('isShow') != '1'){
            // $('#shopMerchantDescBox .phone').attr('data-phone',nowShop.store.phone).html(getLangStr('_SHOP_PHONE_')+': '+nowShop.store.phone);
            // $('#shopMerchantDescBox .address').attr('data-url','map&param='+nowShop.store.id+'-'+nowShop.store.long+'-'+nowShop.store.lat+'-'+encodeURIComponent(nowShop.store.name)+'-'+encodeURIComponent(nowShop.store.adress)).html('<span></span>'+ getLangStr('_SHOP_ADDRESS_') +'：'+nowShop.store.adress);
            // $('#shopMerchantDescBox .openTime').html(getLangStr('_BUSINESS_TIME_')+'：'+nowShop.store.time);
            // $('#shopMerchantDescBox .merchantNotice').html(getLangStr('_SHOP_NOTICE_') + '：'+nowShop.store.store_notice);
            $('#shopMerchantDescBox .phone').html(nowShop.store.phone);
            $('#shopMerchantDescBox .address').attr('data-url','map&param='+nowShop.store.id+'-'+nowShop.store.long+'-'+nowShop.store.lat+'-'+encodeURIComponent(nowShop.store.name)+'-'+encodeURIComponent(nowShop.store.adress)).html('<span></span>'+ nowShop.store.adress);
            $('#shopMerchantDescBox .w1').html(nowShop.store.open_list[0]!="" ? nowShop.store.open_list[0]:getLangStr('_STORE_STATUS_CLOSED'));
            $('#shopMerchantDescBox .w2').html(nowShop.store.open_list[1]!="" ? nowShop.store.open_list[1]:getLangStr('_STORE_STATUS_CLOSED'));
            $('#shopMerchantDescBox .w3').html(nowShop.store.open_list[2]!="" ? nowShop.store.open_list[2]:getLangStr('_STORE_STATUS_CLOSED'));
            $('#shopMerchantDescBox .w4').html(nowShop.store.open_list[3]!="" ? nowShop.store.open_list[3]:getLangStr('_STORE_STATUS_CLOSED'));
            $('#shopMerchantDescBox .w5').html(nowShop.store.open_list[4]!="" ? nowShop.store.open_list[4]:getLangStr('_STORE_STATUS_CLOSED'));
            $('#shopMerchantDescBox .w6').html(nowShop.store.open_list[5]!="" ? nowShop.store.open_list[5]:getLangStr('_STORE_STATUS_CLOSED'));
            $('#shopMerchantDescBox .w7').html(nowShop.store.open_list[6]!="" ? nowShop.store.open_list[6]:getLangStr('_STORE_STATUS_CLOSED'));
            //$('#shopMerchantDescBox .merchantNotice').html(getLangStr('_SHOP_NOTICE_') + '：'+nowShop.store.store_notice);
            //var reduce_html = '';
            // if(nowShop.store.reduce) {
            //     for (var i = 0; i < nowShop.store.reduce.length; ++i) {
            //     	if(i == 0)
            //         	reduce_html += '<span>' + nowShop.store.reduce[i] + '</span>';
            //     	else
            //             reduce_html += '<span>&nbsp;;&nbsp;' + nowShop.store.reduce[i] + '</span>';
            //     }
            //     $('#shopMerchantDescBox .merchantReduce').html(reduce_html);
            // }
            // if(nowShop.store.isverify==1){
            //     $('#shopMerchantDescBox').append('<dd class="merchantVerify">'+ getLangStr('_SHOP_CERTIFICATION_') + getLangStr('_CERTIFIED_') +'</dd>');
            // }
            var str = '';
            if(nowShop.store.delivery){
                if(nowShop.store.delivery_system)
                    str = getLangStr('_PLAT_DIST_');
                else
                    str = getLangStr('_SHOP_DIST_');
                //$('#shopMerchantDescBox .deliveryType').html('配送服务：由 '+(nowShop.store.delivery_system ? '平台' : '店铺')+' 提供配送');
            }else{
                str = getLangStr('_SELF_DIST_');
                //$('#shopMerchantDescBox .deliveryType').html(getLangStr('_DIST_SERVICE_') + ': ' + '本店铺仅支持门店自提');
            }
            $('#shopMerchantDescBox .deliveryType').html(getLangStr('_DIST_SERVICE_') + ': ' + str);
			var tmpCouponList = parseCoupon(nowShop.store.coupon_list,'array');
			var tmpCouponHtml = '';
			if(tmpCouponList['invoice']){
				tmpCouponHtml+= '<dd><em class="merchant_invoice"></em>'+tmpCouponList['invoice']+'</dd>';
			}
			if(tmpCouponList['discount']){
				tmpCouponHtml+= '<dd><em class="merchant_discount"></em>'+tmpCouponList['discount']+'</dd>';
			}
			if(tmpCouponList['minus']){
				tmpCouponHtml+= '<dd><em class="merchant_minus"></em>'+tmpCouponList['minus']+'</dd>';
			}
			if(tmpCouponList['newuser']){
				tmpCouponHtml+= '<dd><em class="newuser"></em>'+tmpCouponList['newuser']+'</dd>';
			}
			if(tmpCouponList['delivery']){
				tmpCouponHtml+= '<dd><em class="delivery"></em>'+tmpCouponList['delivery']+'</dd>';
			}
			if(tmpCouponList['system_minus']){
				tmpCouponHtml+= '<dd><em class="system_minus"></em>'+tmpCouponList['system_minus']+'</dd>';
			}
			if(tmpCouponList['system_newuser']){
				tmpCouponHtml+= '<dd><em class="system_newuser"></em>'+tmpCouponList['system_newuser']+'</dd>';
			}
			$('#shopMerchantCouponBox').html(tmpCouponHtml);
			$('#shopMerchantBox').data('isShow','1');
		}
		pageLoadHides();
	}else if(nav == 'reply'){ //评论

        $('#shopProductBox').hide();
		$('#shopMerchantBox').hide();
		$('#shopCatBar').hide();
		$('#shopMenuBar').show();
        $('#shopReplyBox').show();
        $(document).scrollTop(200);

		if($('#shopReplyBox').data('isShow') != '1'){
			$('#showMoreReply').data('page','2');
			$('#shopReplyBox ul li:eq(0)').addClass('active').siblings().removeClass('active');
			$('#shopReplyBox dl').empty();
			$.post(shopReplyUrl+nowShop.store.id,{showCount:1},function(result){
				$('#shopReplyBox').data('isShow','1').show();
				if(result == '0'){
					$('#noReply').show();
					$('#showMoreReply').hide();
					$('#shopReplyBox ul').hide();
				}else{	
					result = $.parseJSON(result);
					$('#shopReplyBox ul li:eq(0) em').html(result.all_count);
					$('#shopReplyBox ul li:eq(1) em').html(result.good_count);
					$('#shopReplyBox ul li:eq(2) em').html(result.wrong_count);
					$('#shopReplyBox ul').show();
					laytpl($('#shopReplyTpl').html()).render(result.list, function(html){
						$('#shopReplyBox dl').html(html);
					});

					if(result.total > result.now){
						$('#showMoreReply').show();
					}else{
						$('#showMoreReply').hide();
					}
					$('#noReply').hide();
				}

				pageLoadHides();
			});
		}else{
			pageLoadHides();
		}
	}
	// if(!$('#shopMenuBar li.'+nav).hasClass('active')){
		// $('#shopMenuBar li.'+nav).trigger('click');
	// }
	// setTimeout(function(){
		// pageLoadHides();
	// },1000);
}

function showGoodsBySortId(sortId, shopId)
{
	$.getJSON(ajax_url_root+'showGoodsBySortId',{'store_id':shopId, 'sort_id':sortId},function(result){
		laytpl($('#shopProductRightBarTpl').html()).render(result.product_list, function(html){
			$('#shopProductRightBar2 dl').html(html);
		});
		var nowShopCart = $.cookie('shop_cart_'+shopId);
		if(nowShopCart){
			nowShopCartArr = $.parseJSON(nowShopCart);
			productCart=[];
			if(nowShopCartArr.length > 0){
				productCartNumber = productCartMoney = 0;
				for(var i in nowShopCartArr){
					var tmpSpec = [];
					var tmpObj = nowShopCartArr[i].productParam;
					if(tmpObj.length > 0){
						for(var j in tmpObj){
							if(tmpObj[j].type == 'spec'){
								tmpSpec.push(tmpObj[j].id);
							}else if(tmpObj[j].type == 'side_dish'){
                                // for(var k in tmpObj[j].data) {
                                //     tmpSpec.push(tmpObj[j].data[k].dish_id);
                                //     tmpSpec.push(tmpObj[j].data[k].dish_val_id);
                                //     tmpSpec.push(tmpObj[j].data[k].dish_num);
                                // }
                                tmpSpec.push(tmpObj[j].dish_id);
                            }else{
								for(var k in tmpObj[j].data){
									tmpSpec.push(tmpObj[j].data[k].list_id);
									tmpSpec.push(tmpObj[j].data[k].id);
								}
							}
						}
						if(tmpSpec.length > 0){
							var tmpSpecStr = nowShopCartArr[i].productId + '_' + tmpSpec.join('_');
							productCart[tmpSpecStr] = nowShopCartArr[i];
						}else{
							productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
						}
					}else{
						productCart[nowShopCartArr[i].productId] = nowShopCartArr[i];
						$('.product_'+nowShopCartArr[i].productId+' .plus').after('<div class="product_btn number productNum-'+nowShopCartArr[i].productId+'">'+nowShopCartArr[i].count+'</div>').after('<div class="product_btn min"></div>');
					}
					productCartNumber += nowShopCartArr[i].count;
					productCartMoney += nowShopCartArr[i].count * nowShopCartArr[i].productPrice;
				}
				
				//统计购物车功能
				cartFunction('count');//5
			}
			// console.log(productCart);
		}else{
			cartFunction('count');//6
		}
	});
}
function changeTitle(title){
	$(document).attr("title",title);
}

function pageLoadTips(options){
	this.options = {
		showBg:true,
		top:'center',
		left:'center'
	}
	for (var i in options){
		this.options[i] = options[i];
	}
	options = this.options;
	//显示背景
	if(options.showBg){
		$('#pageLoadTipShade').css('background','rgba(216,216,216,0.5)').removeClass('nobg');
	}else{
		$('#pageLoadTipShade').addClass('nobg');
	}
	//显示顶边
	if(options.top == 'center'){
		options.top = (window_height-120)/2;
	}
	//显示顶边
	if(options.left == 'center'){
		options.left = (window_width-120)/2;
	}
	$('#pageLoadTipBox').css({'top':options.top+'px','left':options.left+'px'});
	$('#pageLoadTipShade').css({'height':$(window).height(),'width':$(window).width()}).show();
}

function pageLoadHides(){
	$('#pageLoadTipShade').hide();
}


var myScroll2=null,myScroll3=null;
$(function(){
	$('.dropdown-toggle').click(function(){
		if(choosePage == 'list'){
			isListShow=true;
		}else{
			isCatListShow=true;
		}
		if($(this).hasClass('active')){
			close_dropdown();
			return false;
		}
		close_dropdown();
		
		$(this).addClass('active');
		var nav = $(this).attr('data-nav');
		
		
		$('.dropdown-wrapper').addClass(nav+' active');
		$('.'+nav+'-wrapper').addClass('active');
		
		$('#dropdown_scroller,.dropdown-module').height($('.'+nav+'-wrapper>ul>li').size()*41-1);
		
		if($('#dropdown_scroller').height() < ($(window).height() - 97)*0.5){
			// $('#dropdown_scroller,.dropdown-module').height(($(window).height() - 97)*0.5);
			$('#dropdown_scroller,.dropdown-module').height($('#dropdown_scroller div').height());
		}else if($('#dropdown_scroller').height() < ($(window).height() - 97)*0.8){
			$('#dropdown_scroller,.dropdown-module').height($('#dropdown_scroller').height());
		}else{
			$('#dropdown_scroller,.dropdown-module').height(($(window).height() - 97)*0.8);
			myScroll3 = new IScroll('#dropdown_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
		}
		
		if(!$('#listNavBox').hasClass('fixed')){
			if(choosePage == 'list'){
				if($('#pageList').height() < window_height + $('#listNavBox').offset().top){
					$('#pageList .shade').css('min-height',window_height + $('#listNavBox').offset().top).show();
				}
				$(window).scrollTop(listNavBarTop+5);
				setTimeout(function(){
					$('#listNavBox').addClass('fixed');
					$('#pageList .shade').height($('#pageList').height()+'px').show();
					isShowShade = true;
				},50);
			}else{
				$('#pageCat .shade').height($('#pageCat').height()+'px').show();
			}
		}else{
			$('#pageList .shade').height($('#pageList').height()+'px').show();
			// if($('#pageList').height() < window_height + $('#listNavBox').offset().top){
				// $('#pageList .shade').css('min-height',window_height + $('#listNavBox').offset().top);
			// }
			isShowShade = true;
		}
		
		if($('.'+nav+'-wrapper').find('.active').attr('data-has-sub')){
			$('#dropdown_sub_scroller').html('<div>'+$('.'+nav+'-wrapper').find('.active').find('.sub_cat').html()+'<div>').css('left','160px');
			$('#dropdown_scroller').width('160px');
		}
		myScroll2 = new IScroll('#dropdown_sub_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
	});
	$('#pageList .shade').click(function(){
		$('#listNavBox').removeClass('fixed');
		$('#listNavPlaceHolderBox').hide();
		close_dropdown();
	});
	$('#pageCat .shade').click(function(){
		close_dropdown();
	});
	
	$(document).on('click','.biz-wrapper ul>li, .category-wrapper ul>li',function(){
		$('#dropdown_sub_scroller').css({'overflow':'hide','overflow-y':''});
		$('.biz-wrapper ul>li, .category-wrapper ul>li').removeClass('active');	
		if($(this).attr('data-has-sub')){
			$(this).addClass('active');
			$('#dropdown_sub_scroller').html('<div>'+$(this).find('.sub_cat').html()+'<div>').css('left','160px');
			$('#dropdown_scroller').width('160px');
			if($('#dropdown_sub_scroller>div').height() > $('#dropdown_sub_scroller').height()){
				myScroll2 = new IScroll('#dropdown_sub_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
			}
		}
	});
	// $(document).on('click','.dropdown-list li',function(){
		// if(!$(this).attr('data-has-sub')){
			// alert(222);
			// list_location($(this));
		// }
	// });
});

function close_dropdown(){
	if(choosePage == 'list'){
		isListShow=false;
	}else{
		isCatListShow=false;
	}
	isShowShade = false;
	$('#dropdown_scroller,#dropdown_sub_scroller').css('width','');
	$('.dropdown-toggle').removeClass('active');
	$('.dropdown-wrapper').prop('class','dropdown-wrapper');
	$('#dropdown_scroller,.dropdown-module').css('height','');
	$('#pageList .shade,#pageCat .shade').hide();
	$('#dropdown_sub_scroller').css('left','100%');
	$('#dropdown_scroller>div>ul>li').removeClass('active');
	if(myScroll3){
		myScroll3.destroy();
		myScroll3 = null;
		$('#dropdown_scroller>div').removeAttr('style');
	}
	if(myScroll2){
		myScroll2.destroy();
		myScroll2 = null;
		$('#dropdown_sub_scroller>div').removeAttr('style');
	}
}

function getListGeocoderbefore(type){
	if(type == true){
		if(locationClassicHash == 'address'){
			getGeoconv(arguments[2],arguments[3]);
		}else{
			if(user_long != '0'){
				getListGeocoder();
			}else if($.cookie('userLocationName')){
				user_long = arguments[2];
				user_lat = arguments[3];
				getListGeocoder();
			}else{
				getGeoconv(arguments[2],arguments[3]);
			}
		}
	}else{
		// alert('2222222226666666');
		pageLoadHides();
	}
}

function getGeoconv(lng,lat){
	$.getJSON('https://api.map.baidu.com/geoconv/v1/?coords='+lng+','+lat+'&from=1&to=5&ak=4c1bb2055e24296bbaef36574877b4e2&callback=getListGeoconvBack&jsoncallback=?');
}
function getListGeoconvBack(obj){
	// alert(JSON.stringify(obj.result));
	user_long = obj.result[0].x;
	user_lat = obj.result[0].y;
	getListGeocoder();
}

function getListGeocoder(){
	$.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=getListGeocoderBack&location='+user_lat+','+user_long+'&output=json&pois=1&jsoncallback=?');
}

function getListGeocoderBack(obj){
	if(addressGeocoder == false){
		if(obj.result.pois.length > 0){
			$('#locationText').html(obj.result.pois[0].name);
			$.cookie('userLocationName',obj.result.pois[0].name,{expires:700,path:'/'});
		}else{
			$('#locationText').html(obj.result.addressComponent.street);
			$.cookie('userLocationName',obj.result.addressComponent.street,{expires:700,path:'/'});
		}
		pageLoadHides();
		if(nowPage == 'list'){
			showShopList(true);
		}else if(nowPage == 'shopSearch'){
			showShopSearchList(true);
		}
	}else{
		var tmpName = obj.result.pois.length > 0 ? obj.result.pois[0].name : obj.result.addressComponent.street;
		$('#pageAddressLocationList').show().find('.content dd').data({'long':user_long,'lat':user_lat,'name':tmpName}).find('.name').html(tmpName);
		addressGeocoder = false;
	}
}

function showListData(){
	$.getJSON(ajax_url_root+'ajax_index',function(result){
		/*顶部轮播图*/
		if(result.banner_list){
			laytpl($('#listBannerSwiperTpl').html()).render(result.banner_list, function(html){
				$('#listBanner .swiper-wrapper').html(html);
				if(result.banner_list.length > 1){
					var mySwiper1 = $('#listBanner .swiper-container1').swiper({
						pagination:'#listBanner .swiper-pagination1',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						autoplay:3000,
						autoplayDisableOnInteraction:false,
						simulateTouch:false
					});
				}
				$('#listBanner').show();
			});
		}else{
			$('#listHeader').addClass('fixedRoundBg');
			$('#pageList').css('padding-top','50px');
			$('#listBanner').hide();
		}
		
		/*九宫格*/
		if(result.slider_list){
			laytpl($('#listSliderSwiperTpl').html()).render(result.slider_list, function(html){
				$('#listSlider .swiper-wrapper').html(html);
				if(result.slider_list.length > 8){
					var mySwiper2 = $('.swiper-container2').swiper({
						pagination:'.swiper-pagination2',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						simulateTouch:false
					});
				}
				$('#listSlider').show();
			});
		}else{
			$('#listSlider').hide();
		}
		
		/*三格广告*/
		if(result.adver_list){
			laytpl($('#listRecommendTpl').html()).render(result.adver_list, function(html){
				$('#listRecommend').html(html);
				$('#listRecommend').show();
			});
		}else{
			$('#listRecommend').hide();
		}
		
		/*可选分类*/
		if(result.category_list){
			categoryList = result.category_list;
			cat_url = categoryList[0].cat_url;
			laytpl($('#listCategoryListTpl').html()).render(result.category_list, function(html){
				$('#dropdown_scroller .category-wrapper ul').html(html);
			});
		}
		
		/*可选排序*/
		if(result.sort_list){
			sortList = result.sort_list;
			sort_url = categoryList[0].sort_url;
			laytpl($('#listSortListTpl').html()).render(result.sort_list, function(html){
				$('#dropdown_scroller .sort-wrapper ul').html(html);
			});
		}
		
		/*可选类别*/
		if(result.type_list){
			typeList = result.type_list;
			type_url = categoryList[0].type_url;
			laytpl($('#listTypeListTpl').html()).render(result.type_list, function(html){
				$('#dropdown_scroller .type-wrapper ul').html(html);
			});
		}
		listNavBarTop = $('#listNavBox').offset().top - 50;
	});
}

function list_location(obj){
	close_dropdown();
	if(obj.data('cat_url')){
		obj.addClass('red');
		$('.dropdown-toggle.category .nav-head-name').html(obj.find('span').data('name'));
		if(choosePage == 'cat'){
			$('#catTitle').html(obj.find('span').data('name'));
		}
		cat_url = obj.data('cat_url');
	}else if(obj.data('type_url')){
		obj.addClass('active').siblings('li').removeClass('active');
		$('.dropdown-toggle.type .nav-head-name').html(obj.find('span').data('name'));
		type_url = obj.data('type_url');
	}else if(obj.data('sort_url')){
		obj.addClass('active').siblings('li').removeClass('active');
		$('.dropdown-toggle.sort .nav-head-name').html(obj.find('span').data('name'));
		sort_url = obj.data('sort_url');
	}
	pageLoadTips({showBg:false});
	if(choosePage == 'list'){
		showShopList(true);
	}else{
		showCatShopList(true);
	}
}

var listShopSearchNowPage=0,shopSearchListHasMorePage = true;
function showShopSearchList(newPage){
	isSearchListShow = true;
	if(newPage || listShopSearchNowPage == 0){
		$('#pageShopSearch #storeListLoadTip').show();
		$('#pageShopSearch #storeList .dealcard').empty();

		listShopSearchNowPage = 1;
		shopSearchListHasMorePage = true;
		pageLoadTips({showBg:false});
	}else{
		listShopSearchNowPage++;
	}
	$.getJSON(ajax_url_root+'ajax_list',{user_lat:user_lat,user_long:user_long,page:listShopSearchNowPage,key:$('#pageShopSearchTxt').val()},function(result){
		if(result.store_list && result.store_list.length > 0){
			laytpl($('#listShopTpl').html()).render(result.store_list, function(html){
				if(newPage){
					$('#pageShopSearch #storeList .dealcard').html(html);
					$('#pageShopSearch #storeList').show();
				}else{
					$('#pageShopSearch #storeList .dealcard').append(html);
				}
			});
			if(result.has_more == false){
				shopSearchListHasMorePage = false;
				$('#pageShopSearch #storeListLoadTip').hide();
			}
		}else{
			shopSearchListHasMorePage = false;
			$('#pageShopSearch #storeListLoadTip').hide();
		}
		isSearchListShow = false;
		pageLoadHides();
	});
}

var listShopNowPage=0,listHasMorePage = true;
function showShopList(newPage){
	isListShow = true;
	if(newPage || listShopNowPage == 0){
		$('#pageList #storeListLoadTip').show();
		$('#pageList #storeList .dealcard').empty();

		listShopNowPage = 1;
		listHasMorePage = true;
		if(isFirstShowList == false){
			pageLoadTips();
		}
	}else{
		listShopNowPage++;
	}
	$.getJSON(ajax_url_root+'ajax_list',{cat_url:cat_url,sort_url:sort_url,type_url:type_url,user_lat:user_lat,user_long:user_long,page:listShopNowPage},function(result){
		// console.log(result);
		if(result.store_list && result.store_list.length > 0){
			laytpl($('#listShopTpl').html()).render(result.store_list, function(html){
				if(newPage){
					$('#pageList #storeList .dealcard').html(html);
				}else{
					$('#pageList #storeList .dealcard').append(html);
				}
			});
			if(result.has_more == false){
				listHasMorePage = false;
				$('#pageList #storeListLoadTip').hide();
			}
		}else{
			listHasMorePage = false;
			$('#pageList #storeListLoadTip').hide();
		}
		isListShow = false;
		pageLoadHides();
	});
}

var catShopNowPage=0,catHasMorePage = true;
function showCatShopList(newPage){
	isCatListShow = true;
	if(newPage || catShopNowPage == 0){
		$('#pageCat #storeListLoadTip').show();
		$('#pageCat #storeList .dealcard').empty();		
		catShopNowPage = 1;
		catHasMorePage = true;
		pageLoadTips();
	}else{
		catShopNowPage++;
	}
	$.getJSON(ajax_url_root+'ajax_list',{cat_url:cat_url,sort_url:sort_url,type_url:type_url,user_lat:user_lat,user_long:user_long,page:catShopNowPage},function(result){
		// console.log(result);
		if(result.store_list && result.store_list.length > 0){
			laytpl($('#listShopTpl').html()).render(result.store_list, function(html){
				if(newPage){
					$('#pageCat #storeList .dealcard').html(html);
				}else{
					$('#pageCat #storeList .dealcard').append(html);
				}
			});
			if(result.has_more == false){
				catHasMorePage = false;
				$('#pageCat #storeListLoadTip').hide();
			}
		}else{
			catHasMorePage = false;
			$('#pageCat #storeListLoadTip').hide();
		}
		isCatListShow = false;
		pageLoadHides();
	});
}

function goBackPage(){
	var ahref="";
    switch ($.cookie('path_by_what')) {
		case "1":	//home_index
           ahref = "../wap.php";
			break;
		case "2":   //shop_index
            ahref = "../Wap&c=Shop&a=index";
			break;
		case "3":	//order_list
            ahref = "../wap.php?g=Wap&c=My&a=shop_order_list";
			break;
		case "4":	//order_detail
            ahref = "../wap.php?g=Wap&c=My&a=shop_order_list";
			break;
		case "5":	//confirm_order
            ahref = "../wap.php";
			break;
		case "6":	//confirm_order-> adress
            ahref = "../wap.php";
			break;
        default:
            ahref = "../wap.php";
	}

    window.location.href = ahref;
	// if ($.cookie('path_buy_what')==1){
     //    window.location.href = "../wap.php";
	// }else{

     //    if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50){
     //        if(motify.checkIos()){
     //            $('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
     //            window.history.go(-1);
     //        }else{
     //            window.lifepasslogin.webViewGoBack();
     //        }
     //    }else{
     //        if(document.referrer == ""){
     //            window.location.href = storeUrl;
     //        }else{
     //            window.history.go(-1);
     //        }
     //    }
	// }
}

function changeWechatShare(type,param){
	if(typeof(wxSdkLoad) == "undefined"){
		return false;
	}
	
	if(type == 'plat'){
		param = {
			title: window.shareData.tTitle,
			desc:  window.shareData.tContent,
			link:  window.shareData.sendFriendLink + '&openid=' + userOpenid,
			imgUrl: window.shareData.imgUrl,
		};
	}
	// console.log(param);
	wx.ready(function () {
		wx.onMenuShareAppMessage({
			title: param.title,
			desc: param.desc,
			link: param.link,
			imgUrl: param.imgUrl,
			type: '', // 分享类型,music、video或link，不填默认为link
			dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			success: function () { 
				shareHandle('frined');
				//alert('分享朋友成功');
			},
			cancel: function () { 
				//alert('分享朋友失败');
			}
		});
		wx.onMenuShareTimeline({
			title: param.title,
			link: param.link,
			imgUrl: param.imgUrl,
			success: function () { 
				shareHandle('frineds');
				//alert('分享朋友圈成功');
			},
			cancel: function () { 
				//alert('分享朋友圈失败');
			}
		});
	});
}

/*! fly - v1.0.0 - 2014-12-22
 * https://github.com/amibug/fly
 * Copyright (c) 2014 wuyuedong; Licensed MIT */
!function (a) {
    a.fly = function (b, c) {
        var d = {
            version: "1.0.0",
            autoPlay: !0,
            vertex_Rtop: 20,
            speed: 1.2,
            start: {},
            end: {},
            onEnd: a.noop
        }, e = this, f = a(b);
        e.init = function (a) {
            this.setOptions(a), !!this.settings.autoPlay && this.play()
        }, e.setOptions = function (b) {
            this.settings = a.extend(!0, {}, d, b);
            var c = this.settings, e = c.start, g = c.end;
            f.css({
                marginTop: "0px",
                marginLeft: "0px",
                position: "fixed"
            }).appendTo("body"), null != g.width && null != g.height && a.extend(!0, e, {
                width: f.width(),
                height: f.height()
            });
            var h = Math.min(e.top, g.top) - Math.abs(e.left - g.left) / 3;
            h < c.vertex_Rtop && (h = Math.min(c.vertex_Rtop, Math.min(e.top, g.top)));
            var i = Math.sqrt(Math.pow(e.top - g.top, 2) + Math.pow(e.left - g.left, 2)), j = Math.ceil(Math.min(Math.max(Math.log(i) / .05 - 75, 30), 100) / c.speed), k = e.top == h ? 0 : -Math.sqrt((g.top - h) / (e.top - h)), l = (k * e.left - g.left) / (k - 1), m = g.left == l ? 0 : (g.top - h) / Math.pow(g.left - l, 2);
            a.extend(!0, c, {count: -1, steps: j, vertex_left: l, vertex_top: h, curvature: m})
        }, e.play = function () {
            this.move()
        }, e.move = function () {
            var b = this.settings, c = b.start, d = b.count, e = b.steps, g = b.end, h = c.left + (g.left - c.left) * d / e, i = 0 == b.curvature ? c.top + (g.top - c.top) * d / e : b.curvature * Math.pow(h - b.vertex_left, 2) + b.vertex_top;
            if (null != g.width && null != g.height) {
                var j = e / 2, k = g.width - (g.width - c.width) * Math.cos(j > d ? 0 : (d - j) / (e - j) * Math.PI / 2), l = g.height - (g.height - c.height) * Math.cos(j > d ? 0 : (d - j) / (e - j) * Math.PI / 2);
                f.css({width: k + "px", height: l + "px", "font-size": Math.min(k, l) + "px"})
            }
            f.css({left: h + "px", top: i + "px"}), b.count++;
            var m = window.requestAnimationFrame(a.proxy(this.move, this));
            d == e && (window.cancelAnimationFrame(m), b.onEnd.apply(this))
        }, e.destory = function () {
            f.remove()
        }, e.init(c)
    }, a.fn.fly = function (b) {
        return this.each(function () {
            void 0 == a(this).data("fly") && a(this).data("fly", new a.fly(this, b))
        })
    }
}(jQuery);