<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tutti</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
    <include file="Public:facebook"/>
</head>
<style>
    *{
        margin: 0px;
        font-family: Helvetica;

    }
    body{
        background-color: #F5F5F5;
    }
    .header{
        width: 100%;
        height: 60px;
        background-image: url("{pigcms{$config.site_favicon}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: auto 60px;
        background-color: white;
    }
    .memo,.table{
        width: 90%;
        margin: 10px auto;
        font-size: 1em;
        color: #3f3f3f;
        line-height: 25px;
    }
    .memo img{
        width: 100%;
    }
    .footer{
        background-color: #232323;
        margin: 0 auto;
        height: 50px;
        font-size: 13px;
        text-align: center;
        color: #707070;
        padding-top: 40px;
    }
    .btn,.submit{
        width: 40%;
        height: 40px;
        line-height: 40px;
        color: white;
        background-color: #ffa52d;
        text-align: center;
        cursor: pointer;
        margin: 30px auto;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
    }
    .table p{
        line-height: 30px;
    }
    .table input{
        border:0px;
        border-bottom: 1px solid #3f3f3f;
        width: 200px;
        -moz-border-radius: 0px;
        -webkit-border-radius: 0px;
        border-radius: 0px;
        font-size: 1.1em;
        background-color: #F5F5F5;
    }
    .table input:focus{
        border-bottom: 1px solid #ffa52d;
    }
    .radio{
        display: inline-block;
        position: relative;
        line-height: 18px;
        margin-right: 10px;
        cursor: pointer;
    }
    .radio input{
        display: none;
    }
    .radio .radio-bg{
        display: inline-block;
        height: 18px;
        width: 18px;
        margin-right: 5px;
        padding: 0;
        background-color: #ffa52d;
        border-radius: 100%;
        vertical-align: top;
        box-shadow: 0 1px 15px rgba(0, 0, 0, 0.1) inset, 0 1px 4px rgba(0, 0, 0, 0.1) inset, 1px -1px 2px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .radio .radio-on{
        display: none;
    }
    .radio input:checked + span.radio-on{
        width: 10px;
        height: 10px;
        position: absolute;
        border-radius: 100%;
        background: #FFFFFF;
        top: 4px;
        left: 4px;
        box-shadow: 0 2px 5px 1px rgba(0, 0, 0, 0.3), 0 0 1px rgba(255, 255, 255, 0.4) inset;
        background-image: linear-gradient(#ffffff 0, #e7e7e7 100%);
        transform: scale(0, 0);
        transition: all 0.2s ease;
        transform: scale(1, 1);
        display: inline-block;
    }
    p textarea{
        width: 90%;
        border: 1px solid #333333;
        height: 100px;
        background-color: #F5F5F5;
    }
    .policy{
        margin-top: 20px;
        text-align: center;
    }
    .policy span{
        color: #ffa52d;
        cursor: pointer;
    }
    input.mt[type="radio"], input.mt[type="checkbox"] {
        -webkit-appearance: none;
        width: 1.1rem;
        height: 1.1rem;
        margin: -.07rem 0;
        border-radius: 50%;
        border: .02rem solid #ddd8ce;
        text-align: center;
        vertical-align: middle;
        line-height: 1.1rem;
        outline: 0;
    }
    input.mt[type="checkbox"]:checked {
        background-color: #ffa52d;
        border: 0;
        color: #fff;
    }
    @font-face {
        font-family:base_icon;
        src:url("./tpl/Wap/default/static/css/fonts/base.woff") format("woff"),url("./tpl/Wap/default/static/css/fonts/base.otf")
    }
    input.mt[type="checkbox"]:checked::after {
        content: "✓";
        font-size: 1rem;
        font-family: base_icon;
    }
</style>
<body>
    <include file="Public:google"/>
    <div class="header"></div>
    <div class="table">
        <h2>Application Form – Complete and get started</h2>
        <br/>
        <p>
            Delivering great food to Canadians, saving our customers valuable time and re-investing our profits in our communities.
        </p>
        <br/>
        <p>NAME: <input type="text" name="name">*</p>
        <p>ADDRESS: <input type="text" name="address">*</p>
        <p>CITY: <input type="text" name="city">*</p>
        <p>POSTAL CODE: <input type="text" name="postal_code">*</p>
        <p>EMAIL: <input type="text" name="email">*</p>
        <p>PHONE: <input type="text" name="phone">*</p>
        <p>CURRENT OCCUPATION: <input type="text" name="occ"></p>
        <p>NUMBER OF YEARS: <input type="text" name="noy"></p>
        <br/>
        <p>DO YOU OWN A BUSINESS?
            <label for="dyoab_y" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="dyoab" id="dyoab_y" value="1" checked="checked" />
                Yes,I own a business
                <span class="radio-on"></span>
            </label>
            <label for="dyoab_n" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="dyoab" id="dyoab_n" value="0" />
                No
                <span class="radio-on"></span>
            </label>
        </p>
        <p>IF YES,explain: <input type="text" name="dyoab_ex"></p>
        <p>
            CURRENT ANNUAL INCOME<br>
            <label for="cai_1" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="cai" id="cai_1" value="1" checked="checked" />
                Up to $50K
                <span class="radio-on"></span>
            </label>
            <label for="cai_2" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="cai" id="cai_2" value="2" />
                Over $50K to $75K
                <span class="radio-on"></span>
            </label>
            <label for="cai_3" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="cai" id="cai_3" value="3" />
                Over $75K to $120K
                <span class="radio-on"></span>
            </label>
            <label for="cai_4" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="cai" id="cai_4" value="4" />
                Over $120K
                <span class="radio-on"></span>
            </label>
        </p>
        <br />
        <p>
            NET WORTH: (Total asset minus liabilities)​
            <input type="text" name="net_worth">
        </p>
        <p>
            If you go into business, what amount do you plan to invest?
            $<input type="text" name="invest">
        </p>
        <p>
            Your own capital:
            $<input type="text" name="capital">
        </p>
        <p>
            Borrowed:
            $<input type="text" name="borrowed">
        </p>
        <br>
        <p>
            Do you own your home?
            <label for="dyoyh_y" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="dyoyh" id="dyoyh_y" value="1" checked="checked" />
                Yes
                <span class="radio-on"></span>
            </label>
            <label for="dyoyh_n" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="dyoyh" id="dyoyh_n" value="0" />
                No
                <span class="radio-on"></span>
            </label>
        </p>
        <p>
            Mortgage?
            <label for="mortgage_y" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="mortgage" id="mortgage_y" value="1" checked="checked" />
                Yes
                <span class="radio-on"></span>
            </label>
            <label for="mortgage_n" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="mortgage" id="mortgage_n" value="0" />
                No
                <span class="radio-on"></span>
            </label>
        </p>
        <p>
            Have you ever gone bankrupt?
            <label for="hyegb_y" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="hyegb" id="hyegb_y" value="1" checked="checked" />
                Yes
                <span class="radio-on"></span>
            </label>
            <label for="hyegb_n" class="radio">
                <span class="radio-bg"></span>
                <input type="radio" name="hyegb" id="hyegb_n" value="0" />
                No
                <span class="radio-on"></span>
            </label>
        </p>
        <p>
            If you decide to move forward when can you start?
            <input type="text" name="when_start">
        </p>
        <br>
        <p>
            REFERENCES & CONTACT INFO
        </p>
        <p>
            1.<input type="text" name="raci_1">
        </p>
        <p>
            2.<input type="text" name="raci_2">
        </p>
        <p>
            3.<input type="text" name="raci_3">
        </p>
        <br>
        <p>
            COMMENTS: Please add anything you think is relevant. Work history, personal objectives, tax considerations, outstanding ventures in which you have participated.
        </p>
        <p>
            <textarea name="comments"></textarea>
        </p>
        <div class="policy">
            <input type="checkbox" name="default" value="1" class="mt" checked="checked">
            By clicking the box you are agree to our "<span id="checkPolicy">Privacy Policy</span>"
        </div>
        <div class="submit">Submit</div>
    </div>

    <div class="footer">
        &copy;2019 Kavl Technology Ltd.All rights reserved
    </div>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_public}js/layer/layer.js"></script>
<script>
    $(function () {
        if(!$('input[name="default"]').is(':checked')){
            $('.submit').css('background-color', '#666666');
            $('.submit').unbind();
        }
    });

    $('#checkPolicy').click(function () {
        window.open("./intro/2.html");
    });

    $('input[name="default"]').click(function () {
        if($('input[name="default"]').is(':checked')){
            $('.submit').css('background-color', '#ffa52d');
            $('.submit').bind('click',submit_func);
        }else{
            $('.submit').css('background-color', '#666666');
            $('.submit').unbind();
        }
    });

    $('.submit').click(function () {
        submit_func();
    });

    function submit_func() {
        if($('input[name="default"]').is(':checked')) {
            var send_data = {};
            var is_tip = false;
            var check_list = ['name', 'address', 'city', 'postal_code', 'email', 'phone'];
            $('.table').find('input').each(function () {
                var val = '';
                if ($(this).attr('type') == 'radio') {
                    var t_n = $(this).attr('name');
                    val = $('input:radio[name=' + t_n + ']:checked').val();
                } else {
                    val = $(this).val();
                }

                send_data[$(this).attr('name')] = val;
                if ($.inArray($(this).attr('name'), check_list) >= 0 && val == '') {
                    is_tip = true;
                    $(this).focus();
                }
            });
            send_data['comments'] = $('textarea[name=comments]').val();
            if (is_tip) {
                layer.msg('Please fill in the necessary information');
            } else {
                layer.load(2);
                $.post("{pigcms{:U('market_table')}", send_data, function (data) {
                    layer.closeAll();
                    if (data.status == 1) {
                        layer.msg('We are processing your application now,will get back to you soon.Stay turned!');
                        $('.submit').css('background-color', '#666666');
                        $('.submit').unbind();
                    } else {

                    }
                }, 'json');
            }
        }
    }
</script>
</body>
</html>