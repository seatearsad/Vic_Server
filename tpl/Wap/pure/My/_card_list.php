<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,viewport-fit=cover" />
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
    <include file="Public:facebook"/>
</head>
<style>
    .main{
        width: 100%;
        padding-top: 60px;
        max-width: 640px;
        min-width: 320px;
        margin: 0 auto;
    }

    .gray_line{
        width: 100%;
        height: 2px;
        margin-top: 15px;
        background-color: #cccccc;
    }
    .this_nav{
        width: 100%;
        text-align: center;
        font-size: 1.8em;
        height: 30px;
        line-height: 30px;
        margin-top: 15px;
        position: relative;
    }
    .this_nav span{
        width: 50px;
        height: 30px;
        display:-moz-inline-box;
        display:inline-block;
        -moz-transform:scaleX(-1);
        -webkit-transform:scaleX(-1);
        -o-transform:scaleX(-1);
        transform:scaleX(-1);
        background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
        background-size: auto 20px;
        background-repeat: no-repeat;
        background-position: right center;
        position: absolute;
        left: 8%;
        cursor: pointer;
    }
    .my_money{
        width: 100%;
        text-align: center;
        margin: 40px auto;
        font-size: 4em;
        line-height: 40px;
    }
    .Coupon .Coupon_top{
        background-color: #ffa52d;
    }
    .Coupon .Coupon_end em{
        border: 1px solid #ffa52d;
        color: #ffa52d;
    }
    .Muse,.Expired{
        width: 89%;
        margin: 10px auto;
    }
</style>
<body>
<include file="Public:header"/>
<div class="main">
    <div class="this_nav">
        <span id="back_span"></span>
        {pigcms{$title}
    </div>
    <div class="gray_line"></div>
<section class="Coupon">
    <div class="swiper-slide">
        <ul class="end_ul">
            <li>
                <if condition="$coupon_list">
                    <volist name="coupon_list" id="vo">
                        <dl class="{pigcms{$className}">
                            <dd>
                                <div class="Coupon_top clr">
                                    <div class="fl">
                                        <div class="fltop">
                                            <i>$</i><em>{pigcms{$vo.discount}</em>
                                        </div>
                                        <div class="flend">

                                        </div>
                                    </div>
                                    <div class="fr">
                                        <h2>{pigcms{$vo.name} <php>if($_GET['coupon_type']=='mer'){</php>({pigcms{$vo.merchant})<php>}</php></h2>
                                        <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                                        <php>}else{</php>
                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['order_money'])}
                                        <php>}</php>
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
                </if>
            </li>
        </ul>
    </div>
</section>
    <script type="text/javascript">
        $('#back_span').click(function () {
            window.history.go(-1);
        });
    </script>
</div>
</body>
</html>