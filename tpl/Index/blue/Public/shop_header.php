<div class="header_top">
    <div class="hot cf">
        <div class="loginbar cf">
			<if condition="$now_select_city">
				<div class="span" style="font-size:16px;color:red;padding-right:3px;cursor:default;">{pigcms{$now_select_city.area_name}</div>
				<div class="span" style="padding-right:10px;color:#7d7d7d;">[<a href="{pigcms{:UU('Index/Changecity/index')}">切换城市</a>]</div>
				<div class="span" style="padding-right:10px;">|</div>
			</if>
			<if condition="empty($user_session)">
				<div class="login"><a href="{pigcms{:UU('Index/Login/index')}"> 登录 </a></div>
				<div class="regist"><a href="{pigcms{:UU('Index/Login/reg')}">注册 </a></div>
			<else/>
				<p class="user-info__name growth-info growth-info--nav">
					<span>
						<a rel="nofollow" href="{pigcms{:UU('User/Index/shop_list')}" class="username">{pigcms{$user_session.nickname}</a>
					</span>
					<a class="user-info__logout" href="{pigcms{:UU('Index/Login/logout')}">退出</a>
				</p>
			</if>
			<div class="span">|</div>
			<div class="weixin cf">
				<div class="weixin_txt"><a href="{pigcms{$config.site_url}/topic/weixin.html" target="_blank"> 微信版</a></div>
				<div class="weixin_icon"><p><span>|</span><a href="{pigcms{$config.site_url}/topic/weixin.html" target="_blank">访问微信版</a></p><img src="{pigcms{$config.wechat_qrcode}"/></div>
			</div>
        </div>
        <div class="list">
			<ul class="cf">
				<li>
					<div class="li_txt"><a href="{pigcms{:UU('User/Index/shop_list')}">我的订单</a></div>
					<div class="span">|</div>
				</li>
				<li class="li_txt_info cf">
					<div class="li_txt_info_txt"><a href="{pigcms{:UU('User/Index/shop_list')}">我的信息</a></div>
					<div class="li_txt_info_ul">
						<ul class="cf">
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Index/shop_list')}">我的订单</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Rates/index')}">我的评价</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Collect/index')}">我的收藏</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Point/index')}">我的{pigcms{$config['score_name']}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Credit/index')}">帐户余额</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Adress/index')}">收货地址</a></li>
						</ul>
					</div>
					<div class="span">|</div>
				</li>
				<li class="li_liulan">
					<div class="li_liulan_txt"><a href="#">最近浏览</a></div>	 
					<div class="history" id="J-my-history-menu"></div> 
					<div class="span">|</div>
				</li>
				<li class="li_shop">
					<div class="li_shop_txt"><a href="#">我是商家</a></div>
					<ul class="li_txt_info_ul cf">
						<li><a class="dropdown-menu__item first" rel="nofollow" href="{pigcms{$config.config_site_url}/merchant.php">商家中心</a></li>
						<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{$config.config_site_url}/merchant.php">我想合作</a></li>
					</ul>
				</li>
			</ul>
        </div>
    </div>
</div>
<header class="header cf">
	<div style="border-bottom:2px solid #d9d9d9;">
		<div class="nav cf">
			<div class="logo">
				<a href="{pigcms{$config.site_url}" title="{pigcms{$config.site_name}">
					<img  src="{pigcms{$config.site_logo}" />
				</a>
			</div>
			<div class="search">
				<form action="{pigcms{:U('Group/Search/index')}" method="post" group_action="{pigcms{:U('Group/Search/index')}" meal_action="{pigcms{:U('Meal/Search/index')}">
					<div class="form_sec">
					<div class="form_sec_txt meal">{pigcms{$config.meal_alias_name}</div>
					<div class="form_sec_txt1 group">{pigcms{$config.group_alias_name}</div>
					</div>
					<input name="w" class="input" type="text" placeholder="请输入商品名称、地址等"/>
					<button value="" class="btnclick"><img src="{pigcms{$static_path}images/o2o1_20.png"  /></button>
				</form>
				<div class="search_txt">
					<volist name="search_hot_list" id="vo">
						<a href="{pigcms{$vo.url}"><span>{pigcms{$vo.name}</span></a>
					</volist>
				</div>
			</div>
			<div class="menu">
				<div  class="ment_left" style="display:none">
				  <div class="ment_left_img"><img src="{pigcms{$static_path}images/o2o1_13.png" /></div>
				  <div class="ment_left_txt">随时退</div>
				</div>
				<div  class="ment_left" style="display:none">
				  <div class="ment_left_img"><img src="{pigcms{$static_path}images/o2o1_15.png" /></div>
				  <div class="ment_left_txt">不满意免单</div>
				</div>
				<div  class="ment_left" style="display:none">
				  <div class="ment_left_img"><img src="{pigcms{$static_path}images/o2o1_17.png" /></div>
				  <div class="ment_left_txt">过期退</div>
				</div>
			</div>
		</div>
    </div>
</header>


<div class="w-1200">
	<div class="grid_subHead clearfix">
		<div class="col_main">
			<div class="col_sub">
				<div class="shop_logo"> <!-- -->
					<img src="{pigcms{$merchantarr['imgs'][0]}"/>
				</div>
			</div>
			      <div class="main_wrap">
        <div class="mian_wrap_shop">
          <div class="shop_name">{pigcms{$merchantarr['name']}</div>
          <div class="shop_icon_shop">
		  <if condition="$merchantarr['issign'] eq 1">
		  <span><img src="{pigcms{$static_path}images/shop-shop_03.png"></span>
		  </if>
		  <if condition="$merchantarr['isverify'] eq 1">
		  <span><img src="{pigcms{$static_path}images/shop-shop_05.png"></span>
		  </if>
		  </div>
        </div>
        <div class="main_wrap_left">
			<p class="shop_address">地址：{pigcms{$merchantmstore['areastr']} - {pigcms{$merchantmstore['adress']}</p>
			<div class="shop_icon">
				<ul>
					<li title="商家联系电话">
						<div class="shop_icon_img"><img src="{pigcms{$static_path}images/shop-shop_14.png"></div>
						<div class="shop_icon_img">{pigcms{$merchantmstore['phone']}</div>
					</li>
					<if condition="!empty($merchantmstore['weixin'])">
						<li title="商家微信号">
							<div class="shop_icon_img"><img src="{pigcms{$static_path}images/shop-shop_17.png"></div>
							<div class="shop_icon_img">{pigcms{$merchantmstore['weixin']}</div>
						</li>
					</if>
					<if condition="!empty($merchantmstore['qq'])">
						<li title="商家联系QQ">
							<div class="shop_icon_img"><img src="{pigcms{$static_path}images/shop-shop_19.png"></div>
							<div class="shop_icon_img"><a href="http://wpa.qq.com/msgrd?v=3&uin={pigcms{$merchantmstore['qq']}&site=qq&menu=yes" target="_blank">{pigcms{$merchantmstore['qq']}</a></div>
						</li>
					</if>
					<div style="clear:both"></div>
				</ul>
			</div>
        </div>
        <div style="clear:both"></div>
      </div>
		</div>
		<div class="mobile_href po_ab">
			<div class="mobile_href_img">
				<img src="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'merchant','id'=>$merid))}" width="90" height="90" alt=""/>
				<p style="line-height:20px;font-size:12px;">微信扫一扫访问</p>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div style="clear:both"></div>
</div>