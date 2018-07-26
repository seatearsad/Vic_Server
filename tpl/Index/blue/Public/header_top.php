<div class="header_top">
    <div class="hot cf">
        <div class="loginbar cf">
			<if condition="$now_select_city">
				<div class="span" style="font-size:16px;color:red;padding-right:3px;cursor:default;">{pigcms{$now_select_city.area_name}</div>
				<div class="span" style="padding-right:10px;color:#7d7d7d;">[<a href="{pigcms{:UU('Index/Changecity/index')}">{pigcms{:L('_SWITCH_ADDRESS_')}</a>]</div>
				<div class="span" style="padding-right:10px;">|</div>
			</if>
			<if condition="empty($user_session)">
				<div class="login"><a href="{pigcms{:UU('Index/Login/index')}" style="color:red;"> {pigcms{:L('_B_D_LOGIN_LOGIN1_')} </a></div>
				<div class="regist"><a href="{pigcms{:UU('Index/Login/reg')}"> {pigcms{:L('_B_D_LOGIN_REG2_')} </a></div>
			<else/>
				<p class="user-info__name growth-info growth-info--nav">
					<span>
						<a rel="nofollow" href="{pigcms{:UU('User/Index/shop_list')}" class="username">{pigcms{$user_session.nickname}</a>
					</span>
					<a class="user-info__logout" href="{pigcms{:UU('Index/Login/logout')}">{pigcms{:L('_LOGOUT_TXT_')}</a>
				</p>
			</if>
			<div class="span">|</div>
			<div class="weixin cf">
				<div class="weixin_txt"><a href="{pigcms{$config.config_site_url}/topic/weixin.html" target="_blank"> {pigcms{:L('_WECHAT_EDITION_')} </a></div>
				<div class="weixin_icon"><p><span>|</span><a href="{pigcms{$config.config_site_url}/topic/weixin.html" target="_blank">{pigcms{:L('_GO_TO_WECHAT_')}</a></p><img src="{pigcms{$config.wechat_qrcode}"/></div>
			</div>
            <div class="span">|</div>
            <div class="lang_txt">{pigcms{:L('_LANG_TXT_')} : </div>
            <div class="lang_div">
                <div class="lang_curr">
                    <php>if(C('DEFAULT_LANG') == 'zh-cn') echo L('_CHINESE_TXT_'); else echo L('_ENGLISH_TXT_');</php>
                </div>
                <div class="lang_select lang_cn">中文</div>
                <div class="lang_select lang_en">English</div>
            </div>
        </div>
        <div class="list">

			<ul class="cf" style="float: right;">
				<li>
					<div class="li_txt"><a href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{:L('_MY_ORDER_')}</a></div>
					<div class="span">|</div>
				</li>
				<li class="li_txt_info cf">
					<div class="li_txt_info_txt"><a href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{:L('_MY_MESSAGE_')}</a></div>
					<div class="li_txt_info_ul">
						<ul class="cf">
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{:L('_MY_ORDER_')}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Rates/index')}">{pigcms{:L('_MY_EVAL_')}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Collect/index')}">{pigcms{:L('_MY_COLLECTION_')}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Point/index')}">{pigcms{:L('_MY_TICKET_')}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Credit/index')}">{pigcms{:L('_ACCOUNT_BALANCE_')}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Adress/index')}">{pigcms{:L('_MY_ADDRESS_')}</a></li>
						</ul>
					</div>
					<div class="span">|</div>
				</li>
				<li class="li_liulan">
					<div class="li_liulan_txt"><a href="#">{pigcms{:L('_RECE_BROWSE_')}</a></div>
					<div class="history" id="J-my-history-menu"></div> 
					<div class="span">|</div>
				</li>
				<li class="li_shop">
					<div class="li_shop_txt"><a href="#">{pigcms{:L('_IM_BUSINESSMAN_')}</a></div>
					<ul class="li_txt_info_ul cf">
						<li><a class="dropdown-menu__item first" rel="nofollow" href="{pigcms{$config.config_site_url}/merchant.php">{pigcms{:L('_SHOP_CENTER_')}</a></li>
						<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{$config.config_site_url}/merchant.php">{pigcms{:L('_WANT_TO_COOPER_')}</a></li>
					</ul>
				</li>
			</ul>
        </div>
    </div>
</div>
<header class="header cf">
	<pigcms:one_adver cat_key="index_top">
		<div class="content">
			<div class="banner" style="background:{pigcms{$one_adver.bg_color}">
				<div class="hot"><a href="{pigcms{$one_adver.url}" title="{pigcms{$one_adver.name}"><img src="{pigcms{$one_adver.pic}" /></a></div>
			</div>
		</div>
	</pigcms:one_adver>
    <div class="nav cf">
		<div class="logo">
			<a href="{pigcms{$config.site_url}" title="{pigcms{$config.site_name}">
				<img  src="{pigcms{$config.site_logo}" />
			</a>
			<div></div>
		</div>
		<div class="search">
			<form action="{pigcms{:U('Group/Search/index')}" method="post" group_action="{pigcms{:U('Group/Search/index')}" meal_action="{pigcms{:U('Meal/Search/index')}">
				<div class="form_sec">
					<div class="form_sec_txt group">{pigcms{:L('_LUNCH_TXT_')}</div>
					<div class="form_sec_txt1 meal">{pigcms{:L('_OUT_TXT_')}</div>
				</div>
				<input name="w" class="input" type="text" placeholder="{pigcms{:L('_INPUT_PRODUCT_OR_NAME_')}"/>
				<button value="" class="btnclick"><img src="{pigcms{$static_path}images/o2o1_20.png" /></button>
			</form>
			<div class="search_txt">
				<volist name="search_hot_list" id="vo">
					<a href="{pigcms{$vo.url}"><span>{pigcms{$vo.name}</span></a>
				</volist>
			</div>
		</div>
		
    </div>
    <script src="{pigcms{$static_public}js/lang.js"></script>
</header>