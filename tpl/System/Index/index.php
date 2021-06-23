<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index.css"/>
    <if condition="$config['site_favicon']">
        <link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
    </if>
    <title>Backend Management - {pigcms{$config.site_name}</title>
    <script type="text/javascript">if (self != top) {
            window.top.location.href = "{pigcms{:U('Index/index')}";
        }
        var selected_module = "{pigcms{:strval($_GET['module'])}",
            selected_action = "{pigcms{:strval($_GET['action'])}",
            selected_url = "{pigcms{:urldecode(strval(htmlspecialchars_decode($_GET['url'])))}";</script>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/jquery.colorpicker.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/screenfull.min.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}font-awesome/css/font-awesome.css"/>
</head>
<body style="background:#E2E9EA;overflow:hidden;">
<div id="Main_content">
    <div id="HelpBox" style="width:0px;position:relative;z-index:9999;right:-200px; height:100%; overflow:hidden">
        <!--div id="right_open" style="position:fixed;right:0px;top:80px;cursor:pointer;"><img src="{pigcms{$static_path}images/jt_05.png" /></div-->
        <div id="right_close" style="position:fixed;right:200px;top:80px;display:none;cursor:pointer;"><img
                    src="{pigcms{$static_path}images/jt_04.png"/></div>
        <div class="helpTitle">帮助中心&nbsp;&nbsp;<i class="fa fa-question-circle-o"></i></div>
        <div class="helpContent">
            <div id="indexProfile">
                <div class="helpContentTitle">个人信息</div>
                <div class="helpContentContent" style="padding-top:0px;margin-bottom:0px;">
                    <p><span>会员名：</span>{pigcms{$system_session.account}</p>
                    <p><span>会员组：</span>{pigcms{$system_session.show_account}</p>
                    <p><span>最后登录时间：</span>{pigcms{$system_session.last_time|date='Y-m-d H:i:s',###}</p>
                    <p><span>最后登录IP/地址：</span>{pigcms{$system_session.last_ip|long2ip=###} /
                        {pigcms{$system_session.last.country} {pigcms{$system_session.last.area}</p>
                    <p><span>登录次数：</span>{pigcms{$system_session.login_count}</p>
                </div>
                <hr/>
                <div class="helpContentTitle" style="margin-top:20px;">系统信息</div>
                <div class="helpContentContent" style="padding-top:0px;margin-bottom:0px;">
                    <volist name="server_info" id="vo">
                        <p><span>{pigcms{$key}:</span>{pigcms{$vo}</p>
                    </volist>
                </div>
                <hr/>
                <div
                <if condition="$config['open_help'] eq 0 && $system_session['level'] neq 2">style="display:none;"</if>
                >
                <div class="helpContentTitle" style="margin-top:20px;">官方动态</div>
                <div class="helpContentContent" style="padding-top:0px;margin-bottom:0px;" id="official_news">
                    <li>程序当前版本 :{pigcms{:$release}&nbsp;&nbsp;&nbsp;</li>

                    <script>

                    </script>
                </div>
            </div>
        </div>
        <div id="helpBox" style="display:none;">
            <div class="helpContentTitle">
                帮助文档
            </div>
            <div class="helpContentContent"
            <if condition="$config['open_help'] eq 0 && $system_session['level'] neq 2">style="display:none;"</if>
            >
            <div id="helpContentText"></div>
            <p id="loadHelpContent">文档加载中...</p>
            <p id="emptyHelpContent" style="display:none;">暂无官方帮助文档</p>
            <div id="helpContentBox"></div>
        </div>
    </div>
    <div class="helpContentLine"></div>
    <div class="helpContentContent"
    <if condition="$system_session['level'] neq 2">style="display:none;"</if>
    >
    <p><a href="{pigcms{:U('Updatesys/comein')}" target="blank" id="postWorkorderBtn"><i class="fa fa-edit"></i>&nbsp;&nbsp;提交售后工单</a>
    </p>
    <p><a href="http://wpa.qq.com/msgrd?v=3&uin=1347262060&site=qq&menu=yes" target="_blank"><i class="fa fa-qq"></i>&nbsp;&nbsp;联系售后QQ</a>
    </p>
</div>
</div>
</div>
<!----------------------------------------    主导航     ------------------------------------------------>
<div id="leftMenuBox">
    <div id="leftHideBtn">
        <i class="fa fa-dedent" title="{pigcms{:L('_BACK_LEFT_MIN_')}"></i>
        <i class="fa fa-indent" title="{pigcms{:L('_BACK_LEFT_MAX_')}"></i>
    </div>
    <div id="leftProfile">
        <div class="profile-2-wrapper">
            <div class="profile-2-details">
                <div class="profile-2-img">
<!--                    <if condition="$system_session['level'] eq 2">-->
<!--                        <a href="{pigcms{:U('Index/index')}"><img-->
<!--                                    src="{pigcms{$static_path}images/pigcms_logo.png"/></a>-->
<!--                        <else/>-->
<!--                        &nbsp;-->
<!--                    </if>-->
                </div>
                <ul class="profile-2-info">
                    <li>
                        <h3>{pigcms{$system_session.account}</h3>
                    </li>
                    <li style="color:#999;">{pigcms{$system_session.show_account}</li>
                    <if condition="$now_area">
                        <li style="color:#999;">{pigcms{$now_area.area_name}</li>
                    </if>
                    <li>
                        <div class="btn-group btn-group-sm btn-group-justified">
                            <a href="{pigcms{$config.site_url}" target="_blank"
                               class="toggle-visitors btn btn-dark tt-top tooltipstered" title="TUTTI Website"><i
                                        class="fa fa-tv"></i></a>
                            <a href="{pigcms{:U('Index/cache')}" target="main" class="btn btn-dark tt-top tooltipstered"
                               title="Clear Cache"><i class="fa fa-refresh"></i></a>
                            <a href="{pigcms{:U('Login/logout')}" class="toggle-stats btn btn-dark tt-top tooltipstered"
                               title="Logout"><i class="fa fa-sign-out"></i></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div id="leftNavBox">
            <ul>
                <!--li class="title_bar">
                    <span>导航列表</span>
                </li-->
                <li class="active nav-top">
                    <a href="{pigcms{:U('Index/main')}" target="main" class="auto">
                        <i class="fa fa-line-chart icon"></i>
                        <span class="font-bold">{pigcms{:L('_BACK_OVERVIEW_')}</span>
                    </a>
                </li>
                <volist name="system_menu" id="vo">
                    <li class="nav-top">
                        <a class="auto" href="#">
										<span class="pull-right text-muted">
											<i class="fa fa-fw fa-angle-right text"></i>
											<i class="fa fa-fw fa-angle-down text-active"></i>
										</span>
                            <i class="fa fa-{pigcms{$vo.icon} icon"></i>
                            <span class="font-bold">{pigcms{$vo.name}</span>
                        </a>
                        <ul class="nav nav-sub dk">
                            <li class="sub-title">{pigcms{$vo.name}</li>
                            <volist name="vo['menu_list']" id="voo">
                                <li>
                                    <a target="main" href="{pigcms{:U(ucfirst($voo['module']).'/'.$voo['action'])}"
                                       id="leftmenu_{pigcms{:ucfirst($voo['module'])}_{pigcms{$voo['action']}">
                                        <span>{pigcms{$voo.name}</span>
                                    </a>
                                </li>
                            </volist>
                        </ul>
                    </li>
                </volist>
                <li class="nav-top">
                    <a class="auto" href="#">
										<span class="pull-right text-muted">
											<i class="fa fa-fw fa-angle-right text"></i>
											<i class="fa fa-fw fa-angle-down text-active"></i>
										</span>
                        <i class="fa fa-newspaper-o icon"></i>
                        <span class="font-bold">{pigcms{:L('_LANG_TXT_')}</span>
                    </a>
                    <ul class="nav nav-sub dk">
                        <li class="sub-title">{pigcms{:L('_LANG_TXT_')}</li>
                        <li>
                            <a class="auto" href="javascript:changeLange('en-us')">
                                <span>English</span>
                            </a>
                        </li>
                        <li>
                            <a class="auto" href="javascript:changeLange('zh-cn')">
                                <span>Chinese</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!----------------------------------------    内容     ------------------------------------------------>
<div id="MainBox">
    <div class="main_box">
        <div id="fullscreenBtn" title="{pigcms{:L('_BACK_FULL_SCREEN_')}"><i class="fa fa-expand"></i><i
                    class="fa fa-compress"></i></div>
        <div id="sx" onclick="main_refresh();" title="{pigcms{:L('_BACK_RE_FRAME_')}"></div>
        <iframe name="main" id="Main" src="{pigcms{:U('Index/main')}" frameborder="false" scrolling="auto" width="100%"
                height="auto" allowtransparency="true"></iframe>
    </div>
</div>
<!----------------------------------------    内容     ------------------------------------------------>
<div style="clear:both;"></div>
</div>
<script type="text/javascript" src="{pigcms{$static_path}js/index.js"></script>

<script type="text/javascript">
    $("#right_open").click(function () {
        $("#HelpBox").css('right', '0px');
        $("#HelpBox").css('width', "200px");
        $("#right_open").hide();
        $("#right_close").show();
        $('#MainBox').css('margin-right', '200px');
    })
    $("#right_close").click(function () {
        $("#HelpBox").css('right', '-200px');
        $("#HelpBox").css('width', "0px");
        $("#right_open").show();
        $("#right_close").hide();
        $('#MainBox').css('margin-right', '0px');
    })

    function changeLange(lang) {
        setCookie('lang', lang, 30);
        window.location.reload();
    }
</script>
</body>
</html>