<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<if condition="$config['site_favicon']">
			<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
		</if>
		<!--title>{pigcms{$config.seo_title}</title-->
        <title>{pigcms{:L('_VIC_NAME_')}</title>
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
		<meta name="description" content="{pigcms{$config.seo_description}" />
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script src="{pigcms{$static_path}js/jquery.nav.js"></script>
		<script src="{pigcms{$static_path}js/navfix.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}js/index.activity.js"></script>
		<if condition="$config['wap_redirect']">
			<script>
				if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){
					<if condition="$config['wap_redirect'] eq 1">
						window.location.href = './wap';
					<else/>
						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){
							window.location.href = './wap';
						}
					</if>
				}

			</script>
		</if>
        <include file="Public:facebook"/>
	</head>
    <style>
        *{
            margin: 0px;
            box-sizing: border-box;
            font-family: Helvetica;
            -moz-osx-font-smoothing: grayscale;
        }
        body{
            min-width: 1024px;
            background-color: #F5F5F5;
            color: #3f3f3f;
        }
        .main{
            width: 100%;
            height: 520px;
            background-image: url("./tpl/Static/blue/images/new/app_back.jpg");
            background-size: 100% auto;
            background-position: top;
            margin-bottom: -100px;
        }
        #memo{
            width: 35%;
            margin: 0px auto;
            padding-top: 16%;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }
        #code_div{
            width: 100%;
            display: flex;
            margin-top: 10px;
        }
        #code_div div{
            flex: 1 1 100%;
        }
        .app_store{
            margin-top: 10px;
        }
        #code_div img{
            width: 80%;
        }
        .en_b{
            width: 50%;
            margin: 10px auto;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .face_inst{
            width: 25%;
            margin: 20px auto;
        }
        .face_inst span{
            display: inline-block;
            width: 40%;
            font-weight: bold;
            line-height: 35px;
            padding-left: 40px;
            background-repeat: no-repeat;
            background-size: auto 80%;
            background-position: left center;
        }
        .face_span{
            background-image: url("./tpl/Static/blue/images/new/code/face.png");
        }
        .inst_span{
            background-image: url("./tpl/Static/blue/images/new/code/inst.png");
        }
    </style>
	<body>
        <include file="Public:google"/>
        <include file="Public:header"/>
        <section class="main">
            <div id="memo">
                <div>
                    {pigcms{:L('_NEW_APP_PAGE_T')}<br>
                    <a href="/wap.php" style="color: #3f3f3f;text-decoration: underline;line-height: 25px;">{pigcms{:L('_NEW_APP_PAGE_T_LINK')}</a>
                </div>
                <div id="code_div">
                    <div>
                        <img src="./tpl/Static/blue/images/new/code/app_store.png"/>
                        <img src="./tpl/Static/blue/images/new/Apple_app_store_icon_new.png" style="width: 100%" class="app_store"/>
                    </div>
                    <div class="code_icon">
                        <img src="./tpl/Static/blue/images/new/icon.png" style="border-radius: 30px;margin-top: 20%;"/>
                    </div>
                    <div>
                        <img src="./tpl/Static/blue/images/new/code/google_play.png"/>
                        <img src="./tpl/Static/blue/images/new/AndroidButton_new.png" style="width: 100%" class="app_store"/>
                    </div>
                </div>
                <if condition="C('DEFAULT_LANG') eq 'zh-cn'">
                    <div style="margin-top: 10px;">{pigcms{:L('_NEW_APP_PAGE_B')}</div>
                    <div id="code_div">
                        <div></div>
                        <div class="code_icon">
                            <img src="./tpl/Static/blue/images/new/code/WeChat_Tutti.jpg"/>
                        </div>
                        <div></div>
                    </div>
                </if>
            </div>
            <if condition="C('DEFAULT_LANG') neq 'zh-cn'">
                <div class="en_b">{pigcms{:L('_NEW_APP_PAGE_B')}</div>
                <div class="face_inst">
                    <span class="face_span">Tutti</span>
                    <span class="inst_span">tuttidelivery</span>
                </div>
            </if>
        </section>
        <include file="Public:footer"/>
	</body>

    <script>
        $('.main').height($('.main').width()*0.55);
    </script>
</html>
