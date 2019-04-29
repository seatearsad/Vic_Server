<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>{pigcms{$config.shop_alias_name}_{pigcms{$now_city.area_name}_{pigcms{$config.seo_title}</title>
    <if condition="$now_area">
    	<meta name="keywords" content="{pigcms{$now_area.area_name},{pigcms{$now_circle.area_name},{pigcms{$config.seo_keywords}" />
    <else />
    	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    </if>
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<meta charset="utf-8">
	<link href="{pigcms{$static_path}css/shop_pc.css" rel="stylesheet"/>
    <script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script type="text/javascript">var store_id = '{pigcms{$store['id']}', store_long = '{pigcms{$store.long}',store_lat = '{pigcms{$store.lat}',static_path = "{pigcms{$static_path}";</script>
	<!--[if lte IE 9]>
	<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
	<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
	<![endif]-->
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language={pigcms{:C('DEFAULT_LANG')}"></script>
    <script src="{pigcms{$static_path}js/shop_menu.js"></script>
</head>
    <body style="background: #f5f5f5;">
        <section class="shoptop">
            <div class="shopto_top">
                <div class="w1200 clr">
                    <div class="fl clr">
                        <span class="fl">{pigcms{$shop_select_address}</span>
                        <a href="/shop/change.html" class="fl">[{pigcms{:L('_SWITCH_ADDRESS_')}]</a>
                    </div>
                    <if condition="empty($user_session)">
                    <div class="fr">
                    	<span><a href="{pigcms{:UU('Index/Login/index')}">{pigcms{:L('_B_D_LOGIN_LOGIN1_')}</a> | <a href="{pigcms{:UU('Index/Login/reg')}">{pigcms{:L('_B_D_LOGIN_REG2_')}</a></span>
                    </div>
                    <else />
                    <div class="fr">
                        <span><a href="{pigcms{:UU('Index/Login/index')}">{pigcms{$user_session.nickname}</a> | <a href="{pigcms{:UU('Index/Login/logout')}">{pigcms{:L(_LOGOUT_TXT_)}</a></span>
                    </div>
                    </if>
                </div>
            </div>
            <div class="shopto_end shopto_end2">
                <div class="w1200 clr">
                    <div class="fl img">
                        <a href="/"><img src="{pigcms{$config.site_logo}" width=180 height=51></a>
                    </div>
                    <div class="link fl">
                        <a href="/shop.html" class="on">{pigcms{:L('_HOME_TXT_')}</a><span>|</span><a href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{:L('_MY_ORDER_')}</a>
                    </div>
                    <div class="fr">
                        <input type="text" placeholder="{pigcms{:L('_SEARCH_FOOD_')}" id="keyword">
                        <button id="search">{pigcms{:L('_SEARCH_TXT_')}</button>
                    </div>    
                </div>
            </div>
        </section>
        <section class="details">
            <div class="w1200 clr">
                <div class="fl parent">
                    <div class="img fl">
                        <img src="{pigcms{$store['image']}">
                    </div>
                    <div class="pl15 clr">
                        <div class="title clr">
                            <h2>{pigcms{$store['name']}</h2>
                            <if condition="$store['is_close']">
								<span class="no">{pigcms{:L('_NO_BUSINESS_')}</span>
                            <else />
								<span class="yes">{pigcms{:L('_AT_BUSINESS_')}</span>
                            </if>
                            <if condition="$store['isverify']">
                                <php>
                                    if(C('DEFAULT_LANG') == 'zh-cn')
                                    $img_name = 'sjxq_rec.png';
                                    else
                                    $img_name = 'en_rec.png';
                                </php>
                                <img src="../../static/images/{pigcms{$img_name}" style="float:left;margin-left:15px;" >
                            </if>
                        </div>
                        <div class="score clr">
                            <div class="fl">
                                <div class="atar_Show">
                                    <p></p>
                                </div>
                                <span class="Fraction"><i>{pigcms{$store['star']}</i>分</span>
                            </div>
                            <!--span class="fl">月售{pigcms{$store['month_sale_count']}单</span-->
                        </div>
                        <div class="time">{pigcms{:L('_RECE_TIME_')}：{pigcms{$store['time']}</div>
                    </div>
                    <div class="trans">
                        <div class="trans_n">
                            <ul>
                                <li>
                                    <span class="fl">{pigcms{:L('_SHOP_ADDRESS_')}：</span>
                                    <div class="p62">{pigcms{$store['adress']}</div>     
                                </li>
                                <li>
                                    <span class="fl">{pigcms{:L('_SHOP_PHONE_')}：</span>
                                    <div class="p62">{pigcms{$store['phone']}</div>     
                                </li>
                                <!--li>
                                    <span class="fl">配送服务：</span>
                                    <div class="p62">{pigcms{$store['deliver_name']}</div>     
                                </li-->
                            </ul>
                              
                        </div>
                    </div>
                </div>
                <div class="fr give">
                    <ul class="clr">
                        <!--li>
                            <h2>${pigcms{$store['delivery_price']|floatval}</h2>
                            <p>起送价</p>
                        </li-->
                        <li>
                            <h2>${pigcms{$store['delivery_money']|floatval}</h2>
                            <p>{pigcms{:L('_DELI_PRICE_')}</p>
                        </li>
                        <li>
                            <h2>${pigcms{$store['pack_fee']}</h2>
                            <p>{pigcms{:L('_PACK_PRICE_')}</p>
                        </li>
                        <li>
                            <h2>{pigcms{$store['delivery_time']} {pigcms{:L('_MINUTES_TXT_')}</h2>
                            <p>{pigcms{:L('_DELI_TIME_')}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        
        <section class="variety">
            <div class="w1200 clr">
                     <div class="fl vleft">
                    <div class="vlefttop">
                        <div class="clr change">
                            <a href="/shop/{pigcms{$store['id']}.html">{pigcms{:L('_PRODUCT_TXT_')}</a>
                            <a href="/shop/comment/{pigcms{$store['id']}.html" class="on">{pigcms{:L('_EVALUATE_TXT_')}</a>
                        </div>
                    </div>
                    
                    
                    <div class="comment" >
                        <div class="comment_top">
                            <dl class="clr">
                                <a href="/shop/comment/{pigcms{$store['id']}"><dd <if condition="$tab eq ''">class="on"</if>>{pigcms{:L('_ALL_TXT_')} ({pigcms{$all_count})</dd></a>
                                <a href="/shop/comment/{pigcms{$store['id']}/high"><dd <if condition="$tab eq 'high'">class="on"</if>>{pigcms{:L('_SATISFIED_TXT_')} ({pigcms{$good_count})</dd></a>
                                <a href="/shop/comment/{pigcms{$store['id']}/wrong"><dd <if condition="$tab eq 'wrong'">class="on"</if>>{pigcms{:L('_SATISFIED_NOT_')} ({pigcms{$wrong_count})</dd></a>
                            </dl>
                        </div>
                        <div class="comment_end">
                            <ul>
                            	<volist name="list" id="vo">
                                <li class="clr">
                                    <div class="commentpho clr">
                                        <div class="commentpho_n fl">
                                            <div class="img fl">
                                                <img src="{pigcms{$vo['avatar']}">
                                            </div>
                                            <div class="p70">
                                                <h2>{pigcms{$vo['nickname']}</h2>
                                                <div class="score">
                                                    <div class="atar_Show">
                                                        <p></p>
                                                    </div>
                                                    <span class="Fraction"><i>{pigcms{$vo['score']}</i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fr time">{pigcms{$vo['add_time_hi']}</div>     
                                    </div>
                                    <if condition="$vo['goods']">
                                    <div class="Recommend"> <volist name="vo['goods']" id="name"><span>{pigcms{$name}</span></volist></div>
                                    </if>
                                    <div class="text">{pigcms{$vo['comment']}</div>
                                    <if condition="$vo['merchant_reply_content']">
                                    <div class="Reply">餐厅回复 : {pigcms{$vo['merchant_reply_content']}</div>
                                    </if>
                                </li>
                                </volist>
                            </ul>
                            {pigcms{$page}
                        </div>
                    </div> 
                </div>
                <div class="fr vright">
                    <div class="vright_top">
                       <h2>{pigcms{:L('_SHOP_NOTICE_')}</h2>
                       <div class="text">{pigcms{$store['store_notice']}</div> 
                    </div>
                    <div class="vright_middle">
                        <div class="activity">
                            <dl>
					
                            	<if condition="isset($store['coupon_list']['system_newuser']) AND $store['coupon_list']['system_newuser']">
                                <dd>
                                    <span class="fl platform">首</span>
                                    <div class="a_text">平台首单
                                    <volist name="store['coupon_list']['system_newuser']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['system_minus']) AND $store['coupon_list']['system_minus']">
                                <dd>
                                    <span class="fl reduce">减</span>
                                    <div class="a_text">平台
                                    <volist name="store['coupon_list']['system_minus']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['delivery']) AND $store['coupon_list']['delivery']">
                                <dd>
                                    <span class="fl red">惠</span>
                                    <div class="a_text">配送费
                                    <volist name="store['coupon_list']['delivery']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['discount']) AND $store['coupon_list']['discount']">
                                <dd>
                                    <span class="fl zhe">折</span>
                                    <div class="a_text">店内全场{pigcms{$store['coupon_list']['discount']}折</div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['newuser']) AND $store['coupon_list']['newuser']">
                                <dd>
                                    <span class="fl business">首</span>
                                    <div class="a_text">店铺首单
                                    <volist name="store['coupon_list']['newuser']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['minus']) AND $store['coupon_list']['minus']">
                                <dd>
                                    <span class="fl ticket">减</span>
                                    <div class="a_text">店铺
                                    <volist name="store['coupon_list']['minus']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                            </dl>
                        </div>
                    </div>
                    <div class="vright_end" id="biz-map">
                    </div>  
                </div>
            </div>    
        </section>

		<include file="Public:footer"/>
        <!-- 导航 -->
        <section class="scan">
            <ul>
                <li class="code">
                    <div class="display">
                        <h2>扫描二维码</h2>
                        <p>关注微信 下单优惠更多</p>
                        <img src="{pigcms{$config.wechat_qrcode}" width=122 height=122>
                    </div>
                </li>
                <li class="Return"></li>
            </ul>
        </section>
		<script type="text/javascript">

		</script>
</body>
</html>