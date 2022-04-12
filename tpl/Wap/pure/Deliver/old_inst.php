<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>Instructions & Announcement</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index.css"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
    <script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
</head>
<style>
    body{background-color: white;}
    #banner{
        width: 100%;
        background-color: silver;
        height: 50px;
    }
    #all_list{
        width: 90%;
        margin: 10px auto;
        font-size: 13px;
        color: #666666;
    }
    #list_title{
        text-align: center;
        margin: 20px auto;
    }
    #all_list li{
        padding-left: 20px;
        text-decoration: underline;
        line-height: 24px;
    }
</style>
<body>
<include file="header" />
<div style="height: 60px"></div>
<section id="banner_hei" class="banner">
    <div class="swiper-container swiper-container1">
        <div class="swiper-wrapper">
            <volist name="wap_index_top_adver" id="vo">
                <div class="swiper-slide">
                    <a href="{pigcms{$vo.url}">
                        <img src="{pigcms{$vo.pic}"/>
                    </a>
                </div>
            </volist>
        </div>
        <div class="swiper-pagination swiper-pagination1"></div>
    </div>
</section>

<div id="all_list">
    <div id="list_title">
        Articles & Instructions
    </div>
    <ul>
        <volist name="list" id="doc">
            <a href="{pigcms{:U('Deliver/inst')}&did={pigcms{$doc['id']}">
            <li>{pigcms{$doc.title}</li>
            </a>
        </volist>
    </ul>
</div>
<script type="text/javascript">
    $(function(){
        var banner_height	=	$(window).width()/320;
        banner_height	=	 Math.ceil(banner_height*119);
        $("#banner_hei").css('height',banner_height);

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

    var ua = navigator.userAgent;
    if(!ua.match(/TuttiDeliver/i)) {
        navigator.geolocation.getCurrentPosition(function (position) {
            updatePosition(position.coords.latitude,position.coords.longitude);
        });
    }
    //ios app 更新位置
    function updatePosition(lat,lng){
        var message = '';
        $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
            if(result){
                message = result.message;
            }else {
                message = 'Error';
            }
        });

        return message;
    }
</script>
</body>
</html>