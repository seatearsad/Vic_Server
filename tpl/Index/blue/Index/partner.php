<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<if condition="$config['site_favicon']">
			<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
		</if>
		<!--title>{pigcms{$config.seo_title}</title-->
        <title>{pigcms{:L('_VIC_NAME_')} - {pigcms{:L('_NEW_BECOME_PARTNER_')}</title>
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
        @font-face {
            font-family: 'Montserrat';
            src: url('/static/font/Montserrat-Regular.ttf');
        }
        @font-face {
            font-family: 'Montserrat-bold';
            src: url('/static/font/Montserrat-Bold.otf');
        }
        @font-face {
            font-family: 'Montserrat-light';
            src: url('/static/font/Montserrat-Light.otf');
        }
        *{
            margin: 0px;
            box-sizing: border-box;
            font-family: Montserrat;
            -moz-osx-font-smoothing: grayscale;
        }
        body{
            min-width: 1024px;
            background-color: #F5F5F5;
            color: #3f3f3f;
        }
        .white_line{
            width: 96%;
            height: 3px;
            margin:50px auto;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            background-color: #ffffff;
        }
        .main{
            width: 100%;
            height: 520px;
            background-image: url("./tpl/Static/blue/images/new/partner/partner.jpg");
            background-size: 100% auto;
            background-position: bottom;

        }
        .become_div{
            width: 320px;
            height: 430px;
            background-color: #ffffff;
            margin-top: -340px;
            margin-left: 70px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            text-align: center;
        }
        .become_txt{
            width: 100%;
            height: 40px;
            line-height: 40px;
            font-size: 30px;
            font-weight: bold;
            padding-top: 20px;
            color: #ffa52d;
            box-sizing: content-box;
        }
        .become_btn{
            width: 70%;
            height: 40px;
            margin-left: 15%;
            margin-top: 50px;
            line-height: 40px;
            background-color: #ffa52d;
            font-size: 28px;
            font-weight: bold;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            color: #ffffff;
            cursor: pointer;
        }
        .courier_desc{
            height: 180px;
            display: flex;
            margin-top: 80px;
        }
        .courier_desc .desc_box{
            flex: 1 1 100%;
            margin-left: 50px;
            padding-right: 50px;
        }
        .desc_title{
            width: 100%;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            height: 30px;
        }
        .desc_txt{
            line-height: 20px;
        }
        .middle_img{
            width: 100%;
            height: 520px;
            background-image: url("./tpl/Static/blue/images/new/partner/middle.jpg");
            background-size: 100% auto;
            background-position: center;
        }
        .how_div{
            height: 300px;
            margin-top: 100px;
            text-align: center;
        }
        .how_title{
            font-size: 36px;
            font-weight: bold;
            height: 50px;
        }
        .how_memo{
            display: flex;
            width: 90%;
            margin: 50px auto;
        }
        .how_box{
            flex: 1 1 100%;
            margin-left: 20px;
            padding-right: 20px;
            font-size: 14px;
        }
        .how_img{
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            background-repeat: no-repeat;
            background-size: auto 100%;
            background-position: center;
        }
        .how_memo .how_box:nth-child(1) .how_img{
            background-image: url("./tpl/Static/blue/images/new/partner/order.png");
        }
        .how_memo .how_box:nth-child(2) .how_img{
            background-image: url("./tpl/Static/blue/images/new/partner/cook.png");
        }
        .how_memo .how_box:nth-child(3) .how_img{
            background-image: url("./tpl/Static/blue/images/new/partner/hand_over.png");
        }
        .how_memo .how_box:nth-child(4) .how_img{
            background-image: url("./tpl/Static/blue/images/new/partner/drive.png");
        }
        .reg_desc{
            margin-top: 80px;
            height: 50px;
        }
        .app_now{
            width: 300px;
            height: 50px;
            margin: 0 auto;
            line-height: 50px;
            background-color: #ffa52d;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            font-size: 30px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            cursor: pointer;
        }
        .become_form{
            width: 70%;
            margin: 0 auto;
            margin-top: 10px;
        }
        .become_form input{
            width: 100%;
            height: 30px;
            border-left: 0;
            border-right: 0;
            border-top: 0;
            border-bottom: 2px solid #666666;
            font-size: 14px;
            padding-left: 5px;
            margin-top: 10px;
        }
        .become_form input:focus{
            border-bottom: 2px solid #ffa52d;
        }
    </style>
	<body>
        <include file="Public:header"/>
        <div class="main"></div>
        <div class="become_div" id="become_div">
            <div class="become_txt">
                {pigcms{:L('_NEW_BECOME_PARTNER_')}
            </div>
            <div class="become_form">
                <input type="text" name="store_name" placeholder="{pigcms{:L('_NEW_RESTAURANT_NAME_')}">
                <input type="text" name="store_address" id="address" placeholder="{pigcms{:L('_RESTAURANT_ADDRESS_')}">
                <input type="text" name="first_name" placeholder="{pigcms{:L('_NEW_FIRST_NAME_')}">
                <input type="text" name="last_name" placeholder="{pigcms{:L('_NEW_LAST_NAME_')}">
                <input type="text" name="phone" placeholder="{pigcms{:L('_NEW_PHONE_NUMBER_')}">
                <input type="text" name="email" placeholder="{pigcms{:L('_NEW_EMAIL_ADDRESS_')}">

                <input type="hidden" name="lng" value="0" />
                <input type="hidden" name="lat" value="0" />
                <input type="hidden" name="city_id" value="0" />
            </div>
            <div class="become_btn">
                {pigcms{:L('_NEW_SEND_REQUEST_')}
            </div>
        </div>
        <div class="courier_desc">
            <div class="desc_box">
                <div class="desc_title">
                    {pigcms{:L('_EXPAND_AUDIENCE_')}
                </div>
                <div class="desc_txt">
                    {pigcms{:L('_EXPAND_CONTENT_')}
                </div>
            </div>
            <div class="desc_box">
                <div class="desc_title">
                    {pigcms{:L('_GROW_REVENUE_')}
                </div>
                <div class="desc_txt">
                    {pigcms{:L('_GROW_CONTENT_')}
                </div>
            </div>
            <div class="desc_box">
                <div class="desc_title">
                    {pigcms{:L('_ONLINE_STORE_')}
                </div>
                <div class="desc_txt">
                    {pigcms{:L('_ONLINE_CONTENT_')}
                </div></div>
        </div>
        <div class="white_line"></div>
        <div class="middle_img"></div>
        <div class="how_div">
            <div class="how_title">
                {pigcms{:L('_NEW_HOW_WORKS_')}
            </div>
            <div class="how_memo">
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        {pigcms{:L('_CUSTOMER_PLACE_')}
                    </div>
                </div>
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        {pigcms{:L('_ONCE_CONFIRM_')}
                    </div>
                </div>
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        {pigcms{:L('_ONE_OF_')}
                    </div>
                </div>
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        {pigcms{:L('_OUR_COURIER_')}
                    </div>
                </div>
            </div>
        </div>
        <div class="white_line"></div>
        <div class="reg_desc">
            <div class="app_now">Merchant Sign In</div>
            <div style="text-align: center;margin-top: 10px;font-size: 12px">Existing TUTTI Partner?</div>
        </div>
        <include file="Public:footer"/>
	</body>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
<script>
    $('#address').focus(function () {
        initAutocomplete();
    });

    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), {types: ['geocode'],componentRestrictions: {country: ['ca']}});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        $("input[name='lng']").val(place.geometry.location.lng());
        $("input[name='lat']").val(place.geometry.location.lat());

        var add_com = place.address_components;
        var is_get_city = false;
        for(var i=0;i<add_com.length;i++){
            if(add_com[i]['types'][0] == 'locality'){
                is_get_city = true;
                var city_name = add_com[i]['long_name'];
                $.post("{pigcms{:U('Index/ajax_city_name')}",{city_name:city_name},function(result){
                    if (result.error == 1){
                        $("input[name='city_id']").val(0);
                    }else{
                        $("input[name='city_id']").val(result['info']['city_id']);
                    }
                },'JSON');
            }
        }
    }
    $('.become_btn').click(function () {
        var is_tip = checkForm();
        if(is_tip){
            layer.open({
                title: "{pigcms{:L('_STORE_REMIND_')}",
                time: 1,
                content: "{pigcms{:L('_PLEASE_INPUT_ALL_')}"
            });
        }else{
            var re_data = {};
            $('.become_form').find('input').each(function () {
                re_data[$(this).attr('name')] = $(this).val();
            });
            $.post("{pigcms{:U('Index/partner')}",re_data,function(result){
                if (result.error == 0){
                    layer.open({
                        title: "{pigcms{:L('_STORE_REMIND_')}",
                        time: 2,
                        content: "Success! We will contact you as soon as possible. Thank you!"
                    });
                    $('.become_form').find('input').each(function () {
                        $(this).val('');
                    });
                }
            },'JSON');
        }
    });

    function checkPhone(phone) {
        if(!/^\d{10,}$/.test(phone)){
            return false;
        }
        return true;
    }
    function checkMail(mail) {
        var reg = /\w+[@]{1}\w+[.]\w+/;
        if(!reg.test(mail)){
            return false;
        }
        return true;
    }

    function checkForm() {
        var is_tip = false;
        $('.become_form').find('input').each(function () {
            if($(this).val() == ''){
                is_tip = true;
            }
        });

        if($("input[name='store_name']").val().length < 3){
            is_tip = true;
        }

        if(!checkPhone($("input[name='phone']").val())){
            is_tip = true;
        }

        if(!checkMail($("input[name='email']").val())){
            is_tip = true;
        }

        return is_tip;
    }

    $('.app_now').click(function () {
        //location.hash = "#become_div";
        window.location.href = "./wap.php?g=Wap&c=Storestaff&a=login";
    });
</script>
</html>
