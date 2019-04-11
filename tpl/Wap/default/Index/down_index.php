<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <if condition="$config['site_favicon']">
        <link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
    </if>
    <!--title>{pigcms{$config.seo_title}</title-->
    <title>{pigcms{:L('_VIC_NAME_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
    <script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang.js" charset="utf-8"></script>
    <if condition="$config['wap_redirect']">
        <script>
            if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){

            }else{
                //window.location.href = './';
            }

        </script>
    </if>
</head>
<style>
    *{
        margin: 0px;
        box-sizing: border-box;
        font-family: Helvetica;
        -moz-osx-font-smoothing: grayscale;
        font-size: 100%;
    }
    .back_img{
        width: 100%;
        height: 300px;
        background-image: url("./tpl/Static/blue/images/wap/top.jpg");
        background-repeat: no-repeat;
        background-size:auto 100%;
        border-bottom: 5px solid #ffa52d;
    }
    .logo{
        width: 90px;
        height: 90px;
        margin: -45px auto 0 auto;
        background-image: url("./tpl/Static/blue/images/new/icon.png");
        background-size:100% 100%;
        -moz-border-radius: 15px;
        -webkit-border-radius: 15px;
        border-radius: 15px;
    }
    .star{
        text-align: center;
        margin-top: 10px;
    }
    .star span{
        display: inline-block;
        width: 20px;
        height: 20px;
        background-image:url("./tpl/Static/blue/images/wap/or_star.png");
        background-size:auto 100%;
    }
    .slogan{
        margin-top: 10px;
        text-align: center;
        font-size: 1.2em;
        color: #ffa52d;
        font-weight: initial;
    }
    .desc_txt{
        margin-top: 30px;
        text-align: center;
        font-size: 1.58em;
        font-weight: bold;
    }
    .down_load{
        margin: 30px auto 0 auto;
        text-align: center;
        width: 80%;
        height: 45px;
        line-height: 45px;
        font-size: 1.8em;
        font-weight: bold;
        background-color: #ffa52d;
        color: #ffffff;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        cursor: pointer;
    }
    .con_div{
        margin: 20px auto 0 auto;
        text-align: center;
        color: #999999;
        font-size: 1.1em;
    }
    .con_div span{
        text-decoration: underline;
        cursor: pointer;
    }
</style>
<body>
    <div class="back_img"></div>
    <div class="logo"></div>
    <div class="star">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="slogan">
        Your Online Food Community
    </div>
    <div class="desc_txt">
        Your favorite local restaurants Just a click away
    </div>
    <div class="down_load">
        DOWNLOAD
    </div>
    <div class="con_div">
        Continue in <span>Browser</span>
    </div>
</body>
<script>
    $('.con_div').children('span').click(function () {
        setCookie('first_wap', '1',1);
        window.location.reload();
    });
</script>
</html>