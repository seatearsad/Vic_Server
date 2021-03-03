<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{pigcms{:L('_B_PURE_MY_58_')}</title>
    <meta name="viewport"
          content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.peter.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
<!--    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>-->
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
    <style>
        .address-container {
            font-size: .3rem;
            -webkit-box-flex: 1;
        }

        .kv-line h6 {
            width: 10em;
        }

        .btn-wrapper {
            margin: .2rem .2rem;
            padding: 0;
        }

        .address-wrapper {
            border-bottom: 1px solid #ccc;
        }

        .address-wrapper a {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flex-box;
        }

        .address-select {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flex-box;
            padding-right: .2rem;
            -webkit-box-align: center;
            -webkit-box-pack: center;
            -moz-box-align: center;
            -moz-box-pack: center;
            -ms-box-align: center;
            -ms-flex-pack: justify;
        }

        .list.active dd {
            background-color: #fff5e3;
        }

        .confirmlist {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flex-box;
        }

        .confirmlist li {
            -ms-flex: 1;
            -moz-box-flex: 1;
            -webkit-box-flex: 1;
            height: .88rem;
            line-height: .88rem;
            border-right: 1px solid #C9C3B7;
            text-align: center;
        }

        .confirmlist li a.blacktext {
            color: #000;
        }

        .confirmlist li a.orangetext {
            color: #ffa52d;
        }

        .confirmlist li:last-child {
            border-right: none;
        }

        .main {
            width: 100%;
            padding-top: 60px;
            max-width: 640px;
            min-width: 320px;
            margin: 0 auto;
        }

        .gray_line {
            width: 100%;
            height: 2px;
            margin-top: 15px;
            margin-bottom: 15px;
            background-color: #cccccc;
        }

        .this_nav {
            width: 100%;
            text-align: center;
            font-size: 1.8em;
            height: 30px;
            line-height: 30px;
            margin-top: 15px;
            position: relative;
        }

        .this_nav span {
            width: 50px;
            height: 30px;
            display: -moz-inline-box;
            display: inline-block;
            -moz-transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
            -o-transform: scaleX(-1);
            transform: scaleX(-1);
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 20px;
            background-repeat: no-repeat;
            background-position: right center;
            position: absolute;
            left: 8%;
            cursor: pointer;
        }

        .btn-warning {
            background-color: #ffa52d;
        }

        .btn-warning:visited {
            color: white;
        }

        input.mt[type="radio"]:checked, input.mt[type="checkbox"]:checked {
            background-color: #ffa52d;
        }
        .space_one{
            margin: 20px;
            font-size: 16px;
        }
        dl.list_not_allow{
            background: #e1e1e1;
        }
        .bod{
            font-weight: bold;
        }
        .kv-line {
            margin: 5px 0;
        }
    </style>
    <include file="Public:facebook"/>
</head>
<body id="index">
<include file="Public:header"/>
<div class="main">

    <div id="tips" class="tips"></div>
    <div class="wrapper btn-wrapper">
        <a class="address-add btn btn-larger btn-warning btn-block" href="{pigcms{:U('My/edit_adress',$_GET)}">{pigcms{:L('_ADD_NEW_ADDRESS_')}</a>
    </div>
    <volist name="adress_list_allow" id="vo">
        <dl class="list"  data-type="{pigcms{$vo['is_allow']}">
        <dd class="address-wrapper <if condition=" !$vo['select_url']">dd-padding</if>">
        <if condition="$vo['select_url'] and $vo['is_allow'] eq 1">
            <a class="react" href="{pigcms{$vo.select_url}">
<!--                <div class="address-select"><input class="mt" type="radio" name="addr"-->
<!--                    <if condition="$vo['adress_id'] eq $_GET['current_id']">checked="checked"</if>-->
<!--                    />-->
<!--                </div>-->
        </if>
        <div class="address-container">
            <div class="kv-line bod">
               {pigcms{$vo.name}({pigcms{$vo.phone})
            </div>
            <!--			                <div class="kv-line">-->
            <!--			                    <h6>{pigcms{:L('_B_D_LOGIN_TEL_')}：</h6><p></p>-->
            <!--			                </div>-->
            <!--			                <div class="kv-line">-->
            <!--			                    <h6>Unit：</h6><p>{pigcms{$vo.detail} {pigcms{$vo.city_txt}</p>-->
            <!--			                </div>-->
            <div class="kv-line bod">
               {pigcms{$vo.adress}
            </div>
            <div class="kv-line">
                {pigcms{$vo.detail}
            </div>
            <!--							<if condition="$vo['zipcode']">-->
            <!--								<div class="kv-line">-->
            <!--									<h6>{pigcms{:L('_B_PURE_MY_22_')}：</h6><p>{pigcms{$vo.zipcode}</p>-->
            <!--								</div>-->
            <!--							</if>-->
        </div>
        <if condition="$vo['select_url'] and $vo['is_allow'] eq 1">
            </a>
        </if>
        </dd>
        <dd>
            <ul class="confirmlist">
                <li><a class="blacktext react mj-del" href="{pigcms{$vo.del_url}">{pigcms{:L('_B_PURE_MY_27_')}</a></li>
                <li><a class="orangetext react" href="{pigcms{$vo.edit_url}">{pigcms{:L('_EDIT_TXT_')}</a></li>
            </ul>
        </dd>
        </dl>
    </volist>

    <if condition="$adress_list_not_allow">
    <div class="space_one"> {pigcms{:L('V2_PAGETITLE_ADDRESS_ALLOW')}</div>
    </if>
    <volist name="adress_list_not_allow" id="vo">
        <dl class="list list_not_allow" <if condition=" $vo['is_allow'] eq 0">style="background-color:#fee" data-type="{pigcms{$vo['is_allow']}"</if>>
        <dd class="address-wrapper not_allow <if condition=" !$vo['select_url']">dd-padding</if>">
        <if condition="$vo['select_url'] and $vo['is_allow'] eq '1'">

                <!--                <div class="address-select"><input class="mt" type="radio" name="addr"-->
                <!--                    <if condition="$vo['adress_id'] eq $_GET['current_id']">checked="checked"</if>-->
                <!--                    />-->
                <!--                </div>-->
        </if>
        <div class="address-container" style="margin-left:10px;">
            <div class="kv-line bod">
                {pigcms{$vo.name}({pigcms{$vo.phone})
            </div>
            <!--			                <div class="kv-line">-->
            <!--			                    <h6>{pigcms{:L('_B_D_LOGIN_TEL_')}：</h6><p></p>-->
            <!--			                </div>-->
            <!--			                <div class="kv-line">-->
            <!--			                    <h6>Unit：</h6><p>{pigcms{$vo.detail} {pigcms{$vo.city_txt}</p>-->
            <!--			                </div>-->
            <div class="kv-line bod">
                {pigcms{$vo.adress}
            </div>
            <div class="kv-line">
                {pigcms{$vo.detail}
            </div>
        </div>
        </dd>
        <dd>
            <ul class="confirmlist">
                <li><a class="blacktext react mj-del" href="{pigcms{$vo.del_url}">{pigcms{:L('_B_PURE_MY_27_')}</a></li>
                <li><a class="orangetext react" href="{pigcms{$vo.edit_url}">{pigcms{:L('_EDIT_TXT_')}</a></li>
            </ul>
        </dd>
        </dl>
    </volist>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
    <script src="{pigcms{$static_path}js/common_wap.js"></script>
    <script>
        $(function () {
            $('.mj-del').click(function () {
                var now_dom = $(this);
                if (confirm("{pigcms{:L('_B_PURE_MY_84_')}")) {
                    $.post(now_dom.attr('href'), function (result) {
                        if (result.status == '1') {
                            now_dom.closest('dl').remove();
                        } else {
                            alert(result.info);
                        }
                    });
                }
                return false;
            });
            $('.not_allow').click(function () {
                alert("{pigcms{:L('V2_PAGETITLE_ADDRESS_CLICK')}");
            });

            $('.address-wrapper input.mt').click(function () {

                window.location.href = $(this).closest('a').attr('href');
            });
            $.cookie("user_address", '');
        });
        $('#back_span').click(function () {
            window.history.go(-1);
        });
    </script>
</div>
<include file="Public:footer"/>
</body>
</html>