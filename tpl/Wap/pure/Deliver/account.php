<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>My Account</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script>
    //ios app 更新位置
    function updatePosition(lat,lng){
        var message = '';
	    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
            if(result){
                message = result.message;
            }else {
                message = 'Error';
            }
        },'json');

	    return message;
    }
</script>
</head>
<style>
    body{
        background-color: #F8F8F8;
    }
    .div_line{
        width: 90%;
        margin: 20px auto;
        border-radius: 10px;
        padding: 20px 20px 20px 50px;
        box-sizing: border-box;
        font-size: 14px;
        background: white;
        color: #294068;
    }
    .div_line_profile{
        width: 90%;
        margin: 20px auto;
        border-radius: 10px;
        padding: 20px;
        box-sizing: border-box;
        font-size: 14px;
        background: white;
        color: #294068;
    }
    .div_title{
        font-size: 16px;
        margin-bottom: 10px;
        line-height: 24px;
    }
    .div_desc{
        margin-top: 2px;
        font-size: 12px;
        color: #555555;
    }
    .name_div{
        font-size: 16px;
        font-weight: bolder;
        color: #294068;
        text-align: center;
    }
    .title_icon{
        margin-left: -35px;
        position: absolute;
        display: table-cell;
    }
    .title_label{
        width: 90%;
        height: 50px;
        margin: 0 auto;
        border-radius: 25px;
        background: lightgray;
        line-height: 50px;
        display: flex;
        font-size: 16px;
    }
    .title_label span{
        flex: 1 1 50%;
        text-align: center;
        border-radius: 25px;
    }
    .title_label span.active{
        background-color: #294068;
        color: white;
    }
    .update_btn{
        font-size: 12px;
        background-color: #ffa52d;
        color: white;
        text-align: center;
        line-height: 20px;
        display: inline-block;
        padding: 5px;
        border-radius: 5px;
    }
</style>
</head>
<body>
<include file="header" />
<div class="page_title" style="padding-left: 0">
    <div class="title_label">
        <span class="active" data-id="account">Account</span>
        <span data-id="profile">Profile</span>
    </div>
</div>
<div class="show_div" id="account">
    <div class="name_div">
        {pigcms{$deliver_session.name} {pigcms{$deliver_session.family_name}
    </div>
    <div class="div_line" id="bank">
        <div class="div_title">
            <span class="material-icons title_icon">account_balance</span>
            Banking Info
            <span class="material-icons title_icon" style="right: 20px;">arrow_forward</span>
        </div>
        <div class="div_desc">
            Your banking information is used to deposit your earnings directly to your bank account.
        </div>
    </div>
    <div class="div_line">
        <div class="div_title">
            <span class="material-icons title_icon">location_city</span>
            Delivery City
            <span style="float: right;">{pigcms{$city.area_name}</span>
        </div>
        <div class="div_title" style="margin-top: 20px;">
            <span class="material-icons title_icon">drive_eta</span>
            Vehicle Type
            <span style="float: right;">
                <if condition="$deliver_session['vehicle_type'] eq 0 or $deliver_session['vehicle_type'] eq 1">
                    Car
                </if>
                <if condition="$deliver_session['vehicle_type'] eq 2">
                    Bike
                </if>
                <if condition="$deliver_session['vehicle_type'] eq 3">
                    Motorcycle/Scooter
                </if>
            </span>
        </div>
    </div>
    <div class="div_line">
        <div style="margin-left: -35px;margin-bottom: 10px;color: #555555;">CAR</div>
        <div class="div_title">
            <span class="material-icons title_icon">recent_actors</span>
            Driver's License
            <span style="float: right;">
                <if condition="$deliver_session['group'] eq 1">
                    Approved
                <else />
                    <if condition="$deliver_img['driver_license'] eq ''">
                        Please Update
                    <else />
                        Waiting for Approval
                    </if>
                </if>
            </span>
        </div>
        <div class="div_title" style="margin-top: 20px;">
            <span class="material-icons title_icon">featured_play_list</span>
            Vehicle Insurance
            <span style="float: right;">
                <if condition="$deliver_session['group'] eq 1 and $deliver_img['insurace_expiry_type'] eq 1">
                    Approved
                <else />
                    <if condition="$deliver_img['insurace_expiry_type'] eq 0 or $deliver_img['insurace_expiry_type'] eq 2">
                        <span class="update_btn" data-url="{pigcms{:U('Deliver/update_insurance')}">
                            <span class="material-icons" style="width: auto;vertical-align: middle;">report_problem</span>
                            Update
                        </span>
                    </if>
                </if>
            </span>
            <div class="div_desc">
                <if condition="$deliver_img['update_review'] eq 2 or $deliver_img['update_review'] eq 10">
                    <label style="color: #984447">
                        Waiting for review
                    </label>
                </if>
                <if condition="$deliver_img['update_review'] neq 2 and $deliver_img['update_review'] neq 10 and $deliver_img['insurace_expiry_type'] eq 0">
                    <label style="color: #984447">
                        Expired on {pigcms{$deliver_img['insurace_expiry']}
                    </label>
                </if>
                <if condition="$deliver_img['update_review'] neq 2 and $deliver_img['update_review'] neq 10 and $deliver_img['insurace_expiry_type'] eq 2">
                    <label style="color: #6A6A6A">
                        Expires on {pigcms{$deliver_img['insurace_expiry']}
                    </label>
                </if>
                <if condition="$deliver_img['insurace_expiry_type'] eq 1">
                    Expires on {pigcms{$deliver_img['insurace_expiry']}
                </if>
            </div>
        </div>
        <div class="div_title" style="margin-top: 20px;">
            <span class="material-icons title_icon">featured_play_list</span>
            Work Eligibility
            <span style="float: right;">
                <if condition="$deliver_session['group'] eq 1 and ($deliver_img['certificate_expiry'] eq '-1' or $deliver_img['certificate_expiry_type'] eq 1)">
                    Approved
                <else />
                    <if condition="$deliver_img['certificate_expiry'] neq '-1' and ($deliver_img['certificate_expiry_type'] eq 0 or $deliver_img['certificate_expiry_type'] eq 2)">
                        <span class="update_btn" data-url="{pigcms{:U('Deliver/update_work')}">
                            <span class="material-icons" style="width: auto;vertical-align: middle;">report_problem</span>
                            Update
                        </span>
                    </if>
                </if>
            </span>
            <div class="div_desc">
                <if condition="$deliver_img['certificate_expiry'] eq '-1'">
                    Does not expire
                </if>
                <if condition="$deliver_img['certificate_expiry'] neq '-1' and ($deliver_img['update_review'] eq 1 or $deliver_img['update_review'] eq 10)">
                    <label style="color: #984447">
                        Waiting for review
                    </label>
                </if>
                <if condition="$deliver_img['update_review'] neq 1 and $deliver_img['update_review'] neq 10 and $deliver_img['certificate_expiry'] neq '-1' and $deliver_img['certificate_expiry_type'] eq 0">
                    <label style="color: #984447">
                        Expired on {pigcms{$deliver_img['certificate_expiry']}
                    </label>
                </if>
                <if condition="$deliver_img['update_review'] neq 1 and $deliver_img['update_review'] neq 10 and $deliver_img['certificate_expiry'] neq '-1' and $deliver_img['certificate_expiry_type'] eq 2">
                    <label style="color: #6A6A6A">
                        Expires on {pigcms{$deliver_img['certificate_expiry']}
                    </label>
                </if>
                <if condition="$deliver_img['certificate_expiry'] neq '-1' and $deliver_img['certificate_expiry_type'] eq 1">
                    Expires on {pigcms{$deliver_img['certificate_expiry']}
                </if>
            </div>
        </div>
    </div>
    <div class="div_line" id="change_pwd" style="padding-bottom: 10px;">
        <div class="div_title">
            <span class="material-icons title_icon">lock</span>
            Password
            <span class="material-icons title_icon" style="right: 20px;">arrow_forward</span>
        </div>
    </div>
</div>
<div class="show_div" id="profile">
    <div class="div_line_profile">
        <div style="color: #555555;margin-bottom: 5px;">Full Name</div>
        <div class="div_title">
            {pigcms{$deliver_session.name} {pigcms{$deliver_session.family_name}
        </div>

        <div style="color: #555555;margin-bottom: 5px;margin-top: 20px;">Phone</div>
        <div class="div_title">
            {pigcms{$deliver_session.phone}
        </div>

        <div style="color: #555555;margin-bottom: 5px;margin-top: 20px;">E-mail</div>
        <div class="div_title">
            {pigcms{$deliver_session.email}
        </div>

        <div style="color: #555555;margin-bottom: 5px;margin-top: 20px;">Date of birth</div>
        <div class="div_title">
            {pigcms{$deliver_session.birthday}
        </div>

        <div style="color: #555555;margin-bottom: 5px;margin-top: 20px;">Address</div>
        <div class="div_title">
            {pigcms{$deliver_session.site}
        </div>
    </div>

    <div style="margin: 150px auto 0 auto;width: 85%;color: #555555;">
        Please contact support if you would like to change the above information
    </div>
</div>
<script>
    $('#bank').click(function () {
        location.href = "{pigcms{:U('Deliver/bank_info')}";
    });
    $('#change_pwd').click(function () {
        location.href = "{pigcms{:U('Deliver/change_pwd')}";
    });

    $('.update_btn').click(function () {
        location.href = $(this).data('url');
    });

    var ua = navigator.userAgent;
    $('#mail').click(function () {
        if(!ua.match(/TuttiDeliver/i)) {
            location.href = "mailto:hr@tutti.app";
        }else{
            alert("Please send email to hr@tutti.app");
        }
    });

    $('.title_label').find('span').each(function () {
        $(this).click(function () {
            var showId = $(this).data('id');
            $(this).addClass('active').siblings('span').removeClass('active');
            show_div(showId);
        });
    });

    show_div('account');

    function show_div(id) {
        $('body').find('.show_div').each(function () {
            var currId = $(this).attr('id');

            if(id == currId){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    }
</script>
</body>
</html>