function drop_confirm(msg, url){
    if(confirm(msg)){
        window.location = url;
    }
}
function go(url){
    window.location = url;
}
/* 格式化金额 */
function price_format(price){
    if(typeof(PRICE_FORMAT) == 'undefined'){
        PRICE_FORMAT = '$%s';
    }
    price = number_format(price, 2);

    return PRICE_FORMAT.replace('%s', price);
}
function number_format(num, ext){
    if(ext < 0){
        return num;
    }
    num = Number(num);
    if(isNaN(num)){
        num = 0;
    }
    var _str = num.toString();
    var _arr = _str.split('.');
    var _int = _arr[0];
    var _flt = _arr[1];
    if(_str.indexOf('.') == -1){
        /* 找不到小数点，则添加 */
        if(ext == 0){
            return _str;
        }
        var _tmp = '';
        for(var i = 0; i < ext; i++){
            _tmp += '0';
        }
        _str = _str + '.' + _tmp;
    }else{
        if(_flt.length == ext){
            return _str;
        }
        /* 找得到小数点，则截取 */
        if(_flt.length > ext){
            _str = _str.substr(0, _str.length - (_flt.length - ext));
            if(ext == 0){
                _str = _int;
            }
        }else{
            for(var i = 0; i < ext - _flt.length; i++){
                _str += '0';
            }
        }
    }

    return _str;
}
/* 火狐下取本地全路径 */
function getFullPath(obj)
{
    if(obj)
    {
        //ie
        if (window.navigator.userAgent.indexOf("MSIE")>=1)
        {
            obj.select();
            if(window.navigator.userAgent.indexOf("MSIE") == 25){
                obj.blur();
            }
            return document.selection.createRange().text;
        }
        //firefox
        else if(window.navigator.userAgent.indexOf("Firefox")>=1)
        {
            if(obj.files)
            {
                //return obj.files.item(0).getAsDataURL();
                return window.URL.createObjectURL(obj.files.item(0)); 
            }
            return obj.value;
        }
        return obj.value;
    }
}
/* 转化JS跳转中的 ＆ */
function transform_char(str)
{
    if(str.indexOf('&'))
    {
        str = str.replace(/&/g, "%26");
    }
    return str;
}
//图片垂直水平缩放裁切显示
(function($){
    $.fn.VMiddleImg = function(options) {
        var defaults={
            "width":null,
"height":null
        };
        var opts = $.extend({},defaults,options);
        return $(this).each(function() {
            var $this = $(this);
            var objHeight = $this.height(); //图片高度
            var objWidth = $this.width(); //图片宽度
            var parentHeight = opts.height||$this.parent().height(); //图片父容器高度
            var parentWidth = opts.width||$this.parent().width(); //图片父容器宽度
            var ratio = objHeight / objWidth;
            if (objHeight > parentHeight && objWidth > parentWidth) {
                if (objHeight > objWidth) { //赋值宽高
                    $this.width(parentWidth);
                    $this.height(parentWidth * ratio);
                } else {
                    $this.height(parentHeight);
                    $this.width(parentHeight / ratio);
                }
                objHeight = $this.height(); //重新获取宽高
                objWidth = $this.width();
                if (objHeight > objWidth) {
                    $this.css("top", (parentHeight - objHeight) / 2);
                    //定义top属性
                } else {
                    //定义left属性
                    $this.css("left", (parentWidth - objWidth) / 2);
                }
            }
            else {
                if (objWidth > parentWidth) {
                    $this.css("left", (parentWidth - objWidth) / 2);
                }
                $this.css("top", (parentHeight - objHeight) / 2);
            }
        });
    };
})(jQuery);
function DrawImage(ImgD,FitWidth,FitHeight){
    var image=new Image();
    image.src=ImgD.src;
    if(image.width>0 && image.height>0)
    {
        if(image.width/image.height>= FitWidth/FitHeight)
        {
            if(image.width>FitWidth)
            {
                ImgD.width=FitWidth;
                ImgD.height=(image.height*FitWidth)/image.width;
            }
            else
            {
                ImgD.width=image.width;  
                ImgD.height=image.height;  
            }
        }
        else
        {
            if(image.height>FitHeight)
            {
                ImgD.height=FitHeight;
                ImgD.width=(image.width*FitHeight)/image.height;
            }
            else
            {
                ImgD.width=image.width;
                ImgD.height=image.height;
            }
        }  
    }
}

/**
 * 浮动DIV定时显示提示信息,如操作成功, 失败等
 * @param string tips (提示的内容)
 * @param int height 显示的信息距离浏览器顶部的高度
 * @param int time 显示的时间(按秒算), time > 0
 * @sample <a href="javascript:void(0);" onclick="showTips( '操作成功', 100, 3 );">点击</a>
 * @sample 上面代码表示点击后显示操作成功3秒钟, 距离顶部100px
 * @copyright ZhouHr 2010-08-27
 */
function showTips( tips, height, time ){
    var windowWidth = document.documentElement.clientWidth;
    var tipsDiv = '<div class="tipsClass">' + tips + '</div>';

    $( 'body' ).append( tipsDiv );
    $( 'div.tipsClass' ).css({
        'top' : 200 + 'px',
        'left' : ( windowWidth / 2 ) - ( tips.length * 13 / 2 ) + 'px',
        'position' : 'fixed',
        'padding' : '20px 50px',
        'background': '#EAF2FB',
        'font-size' : 14 + 'px',
        'margin' : '0 auto',
        'text-align': 'center',
        'width' : 'auto',
        'color' : '#333',
        'border' : 'solid 1px #A8CAED',
        'opacity' : '0.90',
        'z-index' : '9999'
    }).show();
    setTimeout( function(){$( 'div.tipsClass' ).fadeOut().remove();}, ( time * 1000 ) );
}

function trim(str) {
    return (str + '').replace(/(\s+)$/g, '').replace(/^\s+/g, '');
}
//弹出框登录
function login_dialog(){
    CUR_DIALOG = ajax_form('login',sl_lang['login'],SITEURL+'/index.php?act=login&inajax=1',360,1);
}

/* 显示Ajax表单 */
function ajax_form(id, title, url, width, model)
{
    if (!width)	width = 480;
    if (!model) model = 1;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('ajax', url);
    d.setWidth(width);
    d.show('center',model);
    return d;
}
//显示一个内容为自定义HTML内容的消息
function html_form(id, title, _html, width, model) {
    if (!width)	width = 480;
    if (!model) model = 0;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents(_html);
    d.setWidth(width);
    d.show('center',0);
    return d;
}
//收藏商品js
function collect_goods(fav_id,jstype,jsobj){
    $.get('index.php?act=index&op=login', function(result){
        if(result=='0'){
            window.location.href = 'index.php?act=login';
        }else{
            var url = 'index.php?act=mfavor&op=add&goods_id='+fav_id;
            $.getJSON(url, function(data){
                if (data.done)
            {
                showDialog(data.msg, 'succ','','','','','','','','',2);
                if(jstype == 'count'){
                    $('[nctype="'+jsobj+'"]').each(function(){
                        $(this).html(parseInt($(this).text())+1);
                    });
                }
                if(jstype == 'succ'){
                    $('[nctype="'+jsobj+'"]').each(function(){
                        $(this).html(sl_lang['collection_success']);
                    });
                }
            }
                else
            {
                showDialog(data.msg, 'notice');
            }
            });
        }
    });
}
/*
 * 为低版本IE添加placeholder效果
 *
 * 使用范例：
 * [html]
 * <input id="captcha" name="captcha" type="text" placeholder="验证码" value="" >
 * [javascrpt]
 * $("#captcha").nc_placeholder();
 *
 * 生效后提交表单时，placeholder的内容会被提交到服务器，提交前需要把值清空
 * 范例：
 * $('[data-placeholder="placeholder"]').val("");
 * $("#form").submit();
 *
 */
(function($) {
    $.fn.nc_placeholder = function() {
        var isPlaceholder = 'placeholder' in document.createElement('input');
        return this.each(function() {
            if(!isPlaceholder) {
                $el = $(this);
                $el.focus(function() {
                    if($el.attr("placeholder") === $el.val()) {
                        $el.val("");
                        $el.attr("data-placeholder", "");
                    }
                }).blur(function() {
                    if($el.val() === "") {
                        $el.val($el.attr("placeholder"));
                        $el.attr("data-placeholder", "placeholder");
                    }
                }).blur();
            }
        });
    };
})(jQuery);

/*
 * 弹出窗口
 */
(function($) {
    $.fn.nc_show_dialog = function(options) {

        var that = $(this);
        var settings = $.extend({}, {width: 480, title: ''}, options);

        var init_dialog = function(title) {
            var _div = that;
            that.addClass("dialog_wrapper");
            that.wrapInner(function(){
                return '<div class="dialog_content" style="background: #FFFFFF;margin: 0px; padding: 0px;">';
            });
            that.wrapInner(function(){
                return '<div class="dialog_body" style="position: relative;">';
            });
            that.find('.dialog_body').prepend('<h3 class="dialog_head" style="cursor: move;"><span class="dialog_title"><span class="dialog_title_icon">'+settings.title+'</span></span><span class="dialog_close_button" style="position: absolute; text-indent: -9999px; cursor: pointer; overflow: hidden;">close</span></h3>');
            that.append('<div style="clear:both;"></div>');

            $(".dialog_close_button").click(function(){
                _div.hide();
            });

            that.draggable();
        };

        if(!$(this).hasClass("dialog_wrapper")) {
            init_dialog(settings.title);
        }
        settings.left = $(window).scrollLeft() + ($(window).width() - settings.width) / 2;
        settings.top  = $(window).scrollTop()  + ($(window).height() - $(this).height()) / 2;
        $(this).attr("style","display:none; z-index: 1100; position: absolute; width: "+settings.width+"px; left: "+settings.left+"px; top: "+settings.top+"px;");
        $(this).show();

    };
})(jQuery);

(function($) {
	$.fn.batchform = function(){
		var formobj = $(this);
		$(formobj).find("[nc_type='batchhandle']").click(function(){
			var checkedobj = $(formobj).find("[nc_type ='batchitem']:checked");
			if(checkedobj.length <= 0){
				showDialog(sl_lang['please_select_operation_records']);
				return false;
			}
			var data = $(this).attr('data-param');
			if(data == undefined  || data.length<=0){
				showDialog(sl_lang['parameter_error']);
				return false;
			}
			eval("data = "+data);
			if(data.url == undefined  || data.url.length<=0){
				showDialog(sl_lang['parameter_error']);
				return false;
			}
			if(data.confirmmsg){
				if(confirm(data.confirmmsg)){
					ajaxpost('batchform', '', '', 'onerror','','',data.url);
				}
			}else{
				ajaxpost('batchform', '', '', 'onerror','','',data.url);
			}
		});
	}
	$.fn.singleform = function(){
		$(this).click(function(){
			var data = $(this).attr('data-param');
			if(data == undefined  || data.length<=0){
				showDialog(sl_lang['parameter_error']);
				return false;
			}
		    eval("data = "+data);
		    if(data.url == undefined  || data.url.length<=0){
				showDialog(sl_lang['parameter_error']);
				return false;
			}
		    if(data.confirmmsg){
				if(confirm(data.confirmmsg)){					
					ajaxget(data.url);
				}
			}else{
				ajaxget(data.url);
			}
		});
	}

	//弹出框
	$('*[nc_type="dialog"]').live('click',function(){
		var id = $(this).attr('dialog_id');
		var title = $(this).attr('dialog_title') ? $(this).attr('dialog_title') : '';
		var url = $(this).attr('uri');
		var width = $(this).attr('dialog_width');
		ajax_form(id, title, url, width,0);
		return false;
	});

})(jQuery);


$(function(){
	// 显示隐藏预览图 start
	$('.show_image').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);
});
///*common_sl.js*///
var SITE_URL = window.location.toString().split('/index.php')[0];
SITE_URL = SITE_URL.replace(/(\/+)$/g, '');
jQuery.extend({
  getCookie : function(sName) {
  	sName = COOKIE_PRE + sName;
    var aCookie = document.cookie.split("; ");
    for (var i=0; i < aCookie.length; i++){
      var aCrumb = aCookie[i].split("=");
      if (sName == aCrumb[0]) return decodeURIComponent(aCrumb[1]);
    }
    return '';
  },
  setCookie : function(sName, sValue, sExpires) {
  	sName = COOKIE_PRE + sName;
    var sCookie = sName + "=" + encodeURIComponent(sValue);
    if (sExpires != null) sCookie += "; expires=" + sExpires;
    document.cookie = sCookie;
  },
  removeCookie : function(sName) {
  	sName = COOKIE_PRE + sName;
    document.cookie = sName + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
  }
});
function ajax_notice(id, title, url, width, model) {
    if (!width)	width = 480;
    if (!model) model = 0;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('ajax_notice', url);
    d.setWidth(width);
    d.show('center',model);
    return d;
}
//显示一个正在等待的消息
function loading_form(id, title, _text, width, model) {
    if (!width)	width = 480;
    if (!model) model = 0;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('loading', { text: _text });
    d.setWidth(width);
    d.show('center',model);
    return d;
}
//显示一个提示消息
function message_notice(id, title, _text, width, model) {
    if (!width)	width = 480;
    if (!model) model = 0;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('message', { type: 'notice', text: _text });
    d.setWidth(width);
    d.show('center',model);
    return d;
}
//显示一个带确定、取消按钮的消息
function message_confirm(id, title, _text, width, model) {
    if (!width)	width = 480;
    if (!model) model = 0;
    var d = DialogManager.create(id);
    d.setTitle(title);
    d.setContents('message', { type: 'confirm', text: _text });
    d.setWidth(width);
    d.show('center',model);
    return d;
}
//显示一个消息 消息的内容为IFRAME方式
function iframe_form(id, title, _url, width, height,fresh) {
    if (!width)	width = 480;
    var rnd=Math.random();
    rnd=Math.floor(rnd*10000);

    var d = DialogManager.create(id);
    d.setTitle(title);
    var _html = "<iframe id='iframe_"+rnd+"' src='" + _url + "' width='" + width + "' height='" + height + "' frameborder='0'></iframe>";
    d.setContents(_html);
    d.setWidth(width + 20);
    d.setHeight(height + 60);
    d.show('center');

    $("#iframe_"+rnd).attr("src",_url);
    return d;
}


//取得COOKIE值
//function getcookie(name){
//	return $.cookie(COOKIE_PRE+name);
//}

//动态加载js，css
//$.include(['http://www.shopnc.net/script/a.js','/css/css.css']);
$.extend({
    include: function(file)
    {
        var files = typeof file == "string" ? [file] : file;
        
        for (var i = 0; i < files.length; i++)
        {
            var name = files[i].replace(/^\s|\s$/g, "");
            var att = name.split('.');
            var ext = att[att.length - 1].toLowerCase();
            var isCSS = ext == "css";
            var tag = isCSS ? "link" : "script";
            var attr = isCSS ? " type='text/css' rel='stylesheet' " : " language='javascript' type='text/javascript' ";
            var link = (isCSS ? "href" : "src") + "='" + SITEURL+'/' + name + "'";
            if ($(tag + "[" + link + "]").length == 0) $('body').append("<" + tag + attr + link + "></" + tag + ">");
        }
    }
});
$(function(){
	if(typeof(SITEURL) == 'string') SITE_URL = SITEURL;//重写SITE_URL
//首页左侧分类菜单
$("#category ul").find("li").each(
	function() {
		$(this).hover(
			function() {
				menu = $("#" + this.id + "_menu");
				menu_height = menu.height();
				if (menu_height < 40) menu.height(60);
				menu_height = menu.height();
				li_top = $(this).position().top;
				if ((li_top > 40) && (menu_height >= li_top)) $(menu).css("top",-li_top+20);
				if ((li_top > 160) && (menu_height >= li_top)) $(menu).css("top",-li_top+40);
				if ((li_top > 240) && (li_top > menu_height)) $(menu).css("top",menu_height-li_top);
				if (li_top > 360) $(menu).css("top",60-menu_height);
				if ((li_top > 40) && (menu_height <= 90)) $(menu).css("top",-20);
				
				menu.show();
				$(this).addClass("a");
			},
			function() {
				$(this).removeClass("a");
				$("#" + this.id + "_menu").hide();
			}
		);
	}
);
});
//pdetail
$(function (){
	$(".nc-detail-style a,.nc-detail-size a").click(function (){
		if($(this).hasClass("current")){
			$(this).removeClass("current");
		}else{
			$(this).addClass("current").siblings().removeClass("current");
		}
	  }); 
	  
  //quantity-add
  $(".quantity-add").click(function (){
      var qValue = parseInt($(".quantity-count").val());
      var tCount = parseInt($(".goods_stock").text());
      var cuValue = qValue + 1;
      if(qValue < tCount){
        $(".quantity-count").val(cuValue);
      }
  });
  //quantity-minus
  $(".quantity-minus").click(function (){
      var qValue = parseInt($(".quantity-count").val());
      var cuValue = 1;
      if(qValue > 1){
        cuValue = qValue - 1;
      }
      $(".quantity-count").val(cuValue);
  });
    //tab-menu
  $(".main-nav li").click(function (){
      var tabLiArr = $(this).parent().find("li");
      var tabShowArr = $(this).parents(".tabbar").nextAll(".child-tab-show");
      var index = $.inArray(this,tabLiArr);
      if($(tabShowArr).eq(index)){
        $(tabLiArr).removeClass("current").eq(index).addClass("current");
        $(tabShowArr).addClass("hide").eq(index).removeClass("hide");
      }
  });
  
})

$(function (){
    var smallPicWp = $(".pd-r-cont a");
    smallPicWp.mouseover(function (){
        var smallSrc = $(this).find("img").attr("src");
        var bigPicWp = $(this).parents(".pd-left").find(".pd-pic a");
        bigPicWp.find("img").attr("src",smallSrc);
    });


    var speed = 300;//滚动图片的速度
    var moverTimer;
    var sWpHeight = smallPicWp.outerHeight();
    $(".products_list .up").click(function (){
        var currWp = $(this).parent().find(".scrollWp");
        currWp.stop(true,true).animate({
            "margin-top":'-='+sWpHeight
        },speed,function(){
             currWp.css("margin-top","0px").find("a").eq(0).appendTo(currWp)
        });
    });
    $(".products_list .down").click(function (){
         var currWp = $(this).parent().find(".scrollWp");
         var currImgWp = currWp.find("a");
         var imgLen = currImgWp.length;
        currWp.stop(true,true).animate({
            "margin-top":'+='+sWpHeight
        },speed,function(){
             currWp.css("margin-top","0px").find("a").eq(imgLen-1).prependTo(currWp)
        });
    });
	
	
	$(".goods-new-r-list").hover(function (){
      $(this).find(".goods-new-info").animate({"top":"90px"},400);
  },function (){
    $(this).find(".goods-new-info").animate({"top":"125px"},400);
  });
});
