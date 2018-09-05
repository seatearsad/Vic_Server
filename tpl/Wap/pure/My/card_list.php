<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>{pigcms{:L('_B_PURE_MY_46_')}</title>
    <link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/swiper-3.3.1.min.css"/>
    <link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/card_new.css"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_path}my_card/js/TouchSlide.1.1.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js" charset="utf-8"></script>
    <!--[if lte IE 9]>
    <script src="scripts/html5shiv.min.js"></script>
    <![endif]-->

</head>
<body>
<section class="Coupon">
    <div id="slideBox" class="slideBox">
        <div class="swiper-container bd" id="swiper-container3" >
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <ul class="end_ul">
                        <li>
                            <if condition="$coupon_list[0]">
                                <volist name="coupon_list[0]" id="vo">
                                    <dl class="Muse">
                                        <dd>
                                            <div class="Coupon_top clr">
                                                <div class="fl">
                                                    <div class="fltop">
                                                        <i>$</i><em>{pigcms{$vo.discount}</em>
                                                    </div>
                                                    <div class="flend">
                                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                                                    </div>
                                                </div>
                                                <div class="fr">
                                                    <h2>{pigcms{$vo.name} <php>if($_GET['coupon_type']=='mer'){</php>({pigcms{$vo.merchant})<php>}</php></h2>
                                                    <p>&nbsp;<!--使用平台：{pigcms{$vo.platform}--></p>
                                                    <p>&nbsp;<!--使用类别：<php>if($vo['cate_name']=='all'){echo "所有";}else{</php>{pigcms{$vo.cate_name}<php>}</php>--></p>
                                                </div>
                                            </div>

                                            <div class="Coupon_end">
                                                <div class="Coupon_x">
                                                    <i>{pigcms{$vo.start_time|date='Y.m.d',###}--{pigcms{$vo.end_time|date='Y.m.d',###}</i>
                                                    <php>if($_GET['coupon_type']=='system'){</php><a href="{pigcms{$vo.url}"><em>{pigcms{:L('_IMMEDIATE_USE_')}</em></a><php>}</php>
                                                </div>
                                                <div class="Coupon_sm">
                                                    <span class="on">{pigcms{:L('_INSTRUCTIONS_TXT_')}</span>
                                                    <div class="Coupon_text overflow">{pigcms{$vo.des}</div>
                                                </div>
                                            </div>
                                            <span class="several">{pigcms{$vo.get_num}</span>
                                            <i class="bj"></i>
                                        </dd>
                                    </dl>
                                </volist>
                                <php>if($_GET['coupon_type']=='mer'){</php><div class="more"><a href="{pigcms{:C('config.site_url')}/wap.php">快去购买吧<span></span></a></div>	<php>}</php>
                            </if>
                                <php>if($_GET['coupon_type']=='system'){</php>
                                <div class="more"><!--a href="{pigcms{:U('Wap/Systemcoupon/index')}">更多好券，去领券中心看看<span></span></a--></div>
                                <php>}else{</php>
                                <div class="overdue"><span>关注更多商家得更多券</span></div>
                                <php>}</php>
                        </li>
                    </ul>
                </div>
                <div class="swiper-slide">
                    <ul class="end_ul">
                        <li>
                            <if condition="$coupon_list[2]">
                                <volist name="coupon_list[2]" id="vo">
                                    <dl class="Expired">
                                        <dd>
                                            <div class="Coupon_top clr">
                                                <div class="fl">
                                                    <div class="fltop">
                                                        <i>$</i><em>{pigcms{$vo.discount}</em>
                                                    </div>
                                                    <div class="flend">
                                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                                                    </div>
                                                </div>
                                                <div class="fr">
                                                    <h2>{pigcms{$vo.name} <php>if($_GET['coupon_type']=='mer'){</php>({pigcms{$vo.merchant})<php>}</php></h2>
                                                    <p>&nbsp;<!--使用平台：{pigcms{$vo.platform}--></p>
                                                    <p>&nbsp;<!--使用类别：<php>if($vo['cate_name']=='all'){echo "所有";}else{</php>{pigcms{$vo.cate_name}<php>}</php>--></p>
                                                </div>
                                            </div>
                                            <div class="Coupon_end">
                                                <div class="Coupon_x">
                                                    <i>{pigcms{$vo.start_time|date='Y.m.d',###}--{pigcms{$vo.end_time|date='Y.m.d',###}</i>

                                                </div>
                                                <div class="Coupon_sm">
                                                    <span class="on">{pigcms{:L('_INSTRUCTIONS_TXT_')}</span>
                                                    <div class="Coupon_text overflow">{pigcms{$vo.des}</div>
                                                </div>
                                            </div>
                                            <span class="several">{pigcms{$vo.get_num}</span>
                                            <i class="bj"></i>
                                        </dd>

                                    </dl>
                                </volist>
                            </if>
                            <div class="overdue">
                                <span>{pigcms{:L('_EXPIRED_TXT_')}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="swiper-slide">
                    <ul class="end_ul">
                        <li>
                            <if condition="$coupon_list[1]">
                                <volist name="coupon_list[1]" id="vo">
                                    <dl class="Use">
                                        <dd>
                                            <div class="Coupon_top clr">
                                                <div class="fl">
                                                    <div class="fltop">
                                                        <i>$</i><em>{pigcms{$vo.discount}</em>
                                                    </div>
                                                    <div class="flend">
                                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                                                    </div>
                                                </div>
                                                <div class="fr">
                                                    <h2>{pigcms{$vo.name} <php>if($_GET['coupon_type']=='mer'){</php>({pigcms{$vo.merchant})<php>}</php></h2>
                                                    <p>&nbsp;<!--使用平台：{pigcms{$vo.platform}--></p>
                                                    <p>&nbsp;<!--使用类别：<php>if($vo['cate_name']=='all'){echo "所有";}else{</php>{pigcms{$vo.cate_name}<php>}</php>--></p>
                                                </div>
                                            </div>
                                            <div class="Coupon_end">
                                                <div class="Coupon_x">
                                                    <i>{pigcms{$vo.start_time|date='Y.m.d',###}--{pigcms{$vo.end_time|date='Y.m.d',###}</i>

                                                </div>
                                                <div class="Coupon_sm">
                                                    <span class="on">{pigcms{:L('_INSTRUCTIONS_TXT_')}</span>
                                                    <div class="Coupon_text overflow">{pigcms{$vo.des}</div>
                                                </div>
                                            </div>
                                            <span class="several">{pigcms{$vo.get_num}</span>
                                            <i class="bj"></i>
                                        </dd>
                                    </dl>
                                </volist>
                            </if>
                            <div class="overdue">
                                <span>{pigcms{:L('_AL_USED_')}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="swiper-slide">
                    <div style="width:350px; margin: 50px auto 10px;">
                        <span>{pigcms{:L('_INPUT_EXCHANGE_CODE_')}：</span><input style="height: 24px; width: 150px;" type="text" class="input" name="code" value=""  autocomplete="off">
                    </div>
                    <div id="exchange" style="width:150px;margin: 20px auto;border: 1px solid;text-align: center;cursor: pointer">
                        {pigcms{:L('_EXCHANGE_TXT_')}
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-container hd" id="swiper-container2" >
            <div class="swiper-wrapper">
                <div class="swiper-slide active-nav">
                    {pigcms{:L('_NOT_USED_')} (<i>3</i>)
                </div>
                <div class="swiper-slide">
                    {pigcms{:L('_EXPIRED_TXT_')} (<i>3</i>)
                </div>
                <div class="swiper-slide">
                    {pigcms{:L('_AL_USED_')} (<i>3</i>)
                </div>
                <div class="swiper-slide">
                    {pigcms{:L('_EXCHANGE_COUPON_')}
                </div>
            </div>
        </div>
    </div>
</section>
</body>

<script type="text/javascript">
    // 使用次数
    var u=0;
    var s=0;
    var e=0;
    $('.Muse dd .several').each(function(){
        var value = $(this).html().replace(/[^0-9]/ig,"");
        u+=parseInt(value);
    });
    $('.Expired dd .several').each(function(){
        var value = $(this).html().replace(/[^0-9]/ig,"");
        s+=parseInt(value);
    });
    $('.Use dd .several').each(function(){
        var value = $(this).html().replace(/[^0-9]/ig,"");
        e+=parseInt(value);
    });

    $(".hd .swiper-slide:nth-child(1)").find("i").text(u)
    $(".hd .swiper-slide:nth-child(2)").find("i").text(s)
    $(".hd .swiper-slide:nth-child(3)").find("i").text(e)


    $(".Coupon_sm").each(function(){
        $(this).find("span").click(function(){
            if($(this).hasClass("on")){
                $(this).removeClass("on")
                $(this).siblings(".Coupon_text").removeClass("overflow");
                $(this).parents("dd").siblings().find(".Coupon_sm span").addClass("on");
                $(this).parents("dd").siblings().find(".Coupon_sm .Coupon_text").addClass("overflow");
            }else{
                $(this).addClass("on")
                $(this).siblings(".Coupon_text").addClass("overflow");
            }

        })
    })

</script>

<script>
    var mySwiper2 = new Swiper('#swiper-container2',{
        watchSlidesProgress : true,
        watchSlidesVisibility : true,
        slidesPerView : 4,
        onTap: function(){
            mySwiper3.slideTo( mySwiper2.clickedIndex)
        }
    })
    var mySwiper3 = new Swiper('#swiper-container3',{
        autoHeight: true,

        onSlideChangeStart: function(){
            updateNavPosition()
        }
    })

    mySwiper3.slideTo({pigcms{$_GET['slide']});

    function updateNavPosition(){
        $('#swiper-container2 .active-nav').removeClass('active-nav')
        var activeNav = $('#swiper-container2 .swiper-slide').eq(mySwiper3.activeIndex).addClass('active-nav');
        if (!activeNav.hasClass('swiper-slide-visible')) {
            if (activeNav.index()>mySwiper2.activeIndex) {
                var thumbsPerNav = Math.floor(mySwiper2.width/activeNav.width())-1
                mySwiper2.slideTo(activeNav.index()-thumbsPerNav)
            }
            else {
                mySwiper2.slideTo(activeNav.index())
            }
        }
    }

    //garfunkel add
    $("#exchange").click(function(){
        var code = $("input[name='code']").val();
        if(code == ""){
            alert("{pigcms{:L('_INPUT_EXCHANGE_CODE_')}");
        } else{
            exchange_code(code);
        }
    })

    function exchange_code(code){
        $.ajax({url:"{pigcms{:U('My/exchangeCode')}",type:"post",data:"code="+code,dataType:"json",success:function(data){
                if(data.error_code == 0){
                    alert('success');
                    window.location.reload();
                }else{
                    alert(data.msg);
                }
            }
        });
    }
</script>

</html>