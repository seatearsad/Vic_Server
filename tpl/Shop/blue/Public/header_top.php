<div class="header_top">
    <div class="hot cf">
        <div class="loginbar cf">
			<if condition="$now_select_city">
				<div class="span" style="font-size:16px;color:red;padding-right:3px;cursor:default;">{pigcms{$now_select_city.area_name}</div>
				<div class="span" style="padding-right:10px;color:#7d7d7d;">[<a href="{pigcms{:UU('Index/Changecity/index')}">{pigcms{:L('_SWITCH_ADDRESS_')}</a>]</div>
				<div class="span" style="padding-right:10px;">|</div>
			</if>
			<if condition="empty($user_session)">
				<!--div class="login"><a href="{pigcms{:UU('Index/Login/index')}" style="color:red;"> {pigcms{:L('_B_D_LOGIN_LOGIN1_')} </a></div>
				<div class="regist"><a href="{pigcms{:UU('Index/Login/reg')}"> {pigcms{:L('_B_D_LOGIN_REG2_')} </a></div-->
			<else/>
				<p class="user-info__name growth-info growth-info--nav">
					<span>
						<a rel="nofollow" href="{pigcms{:UU('User/Index/shop_list')}" class="username">{pigcms{$user_session.nickname}</a>
					</span>
					<a class="user-info__logout" href="{pigcms{:UU('Index/Login/logout')}">{pigcms{:L('_LOGOUT_TXT_')}</a>
				</p>
                <div class="span">|</div>
			</if>

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
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Index/index')}">{pigcms{:L('_MY_ORDER_')}</a></li>
							<!--li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Rates/index')}">{pigcms{:L('_MY_EVAL_')}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Collect/index')}">{pigcms{:L('_MY_COLLECTION_')}</a></li-->
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
<script src="{pigcms{$static_public}js/lang.js"></script>