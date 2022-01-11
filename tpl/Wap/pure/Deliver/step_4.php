<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>{pigcms{:L('_COURIER_CENTER_')}</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    body {
        padding: 0px;
        margin: 0px auto;
        font-size: 14px;
        min-width: 320px;
        max-width: 100%;
        background-color: #f4f4f4;
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    section{
        position: absolute;
        top: 7%;
        width: 100%;
        font-size: 10px;
        color: #666666;
    }
    #step_now{
        width:80%;
        margin: 20px auto;
        font-size: 0;
    }
    #step_now div{
        font-size: 10px;
        text-align: right;
    }
    #step_now ul{
        margin-top: 2px;
    }
    #step_now li{
        display: inline-block;
        width: 25%;
        height: 5px;
        background-color: #F4F4F4;
    }
    #step_now li:nth-child(1).act{
        background-color: #ffde59;
    }
    #step_now li:nth-child(2).act{
        background-color: #ffbd59;
    }
    #step_now li:nth-child(3).act{
        background-color: #ffa52d;
    }
    #step_now li:nth-child(4).act{
        background-color: #ffa99a;
    }
    #memo{
        width:80%;
        margin: 20px auto 5px auto;
        text-align: center;
    }
    #address{
        width: 70%;
        margin: 10px auto;
        border-radius: 5px;
        border: 2px solid #ffa52d;
        text-align: center;
        padding: 10px 10px;
        box-sizing: border-box;
    }
    input{
        background-color: #ffa52d;
        color: white;
        padding: 5px 5px;
        border-radius: 3px;
    }
    #memo_up{
        width:90%;
        font-size: 12px;
        line-height: 1.3;
        margin: 60px auto 5px auto;
    }
    #memo_up a{
        text-decoration: underline;
        color: #666666;
    }
    .y_c{
        color: #ffa52d;
    }
    .text_b{
        font-weight: bold;
    }
    .status_div{
        background-color: white;
        border-radius: 10px;
        padding: 20px 20px 10px 20px;
        margin-top: 20px;
    }
    .status_title{
        color: #333333;
        font-size: 20px;
        font-weight: bold;
    }
    .status_show{
        line-height: 30px;
        font-size: 18px;
        margin-top: 10px;
    }
    .status_show .material-icons{
        vertical-align: top;
        margin-top: 2px;
    }
    .status_txt{
        padding-left: 25px;
        height: 50px;
        font-size: 18px;
    }
    .upload_btn{
        float: right;
        background-color: #ffa52d;
        padding: 2px 5px;
        color: white;
        border-radius: 5px;
        font-weight: normal;
    }
    .activate_btn{
        width: 90%;
        line-height: 35px;
        margin-left: 5%;
        margin-top: 30px;
        background-color: #ffa52d;
        text-align: center;
        font-size: 16px;
        color: white;
        border-radius: 6px;
        cursor: pointer;
    }
    .refresh{
        float: right;
        margin-top: 30px;
        margin-right: 20px;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<div class="refresh" id="refresh_btn">
    <span class="material-icons" style="color: #294068;font-size: 26px;">restart_alt</span>
</div>
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px">{pigcms{:L('_ND_BECOMEACOURIER_')}</div>
    </div>
    <div id="memo_up">
        <span class="status_title">Application Status</span>

        <div class="status_div">
            <div class="status_title">
                <span>Required Documents</span>
                <if condition="$userImg['upload_status'] eq 0 or $userImg['upload_status'] eq 3">
                    <span class="upload_btn" id="upload_doc">Upload</span>
                    <else />
                    <!--span class="material-icons" style="float: right">chevron_right</span-->
                </if>
            </div>
            <if condition="$userImg['upload_status'] eq 0">
                <div class="status_show" style="color: #ffa52d">
                <span class="material-icons">info</span><!--info check_circle check_circle_outline-->
                Incomplete! Please upload
                </div>
                <div class="status_txt">
                    We'll start reviewing your application when you upload all documents.
                </div>
            </if>
            <if condition="$userImg['upload_status'] eq 1">
                <div class="status_show" style="color: #72AB29">
                    <span class="material-icons">check_circle_outline</span><!--info check_circle check_circle_outline-->
                    Uploaded! Waiting for review
                </div>
                <div class="status_txt">
                    We'll notify you when we finish reviewing your application.
                </div>
            </if>
            <if condition="$userImg['upload_status'] eq 2">
                <div class="status_show" style="color: #72AB29">
                    <span class="material-icons">check_circle</span><!--info check_circle check_circle_outline-->
                    Approved!
                </div>
                <div class="status_txt">

                </div>
            </if>
            <if condition="$userImg['upload_status'] eq 3">
                <div class="status_show" style="color: #ffa52d">
                <span class="material-icons">info</span><!--info check_circle check_circle_outline-->
                Review Disapproved
                </div>
                <div class="status_txt">
                    {pigcms{$userImg['review_desc']}
                </div>
            </if>
        </div>
        <div class="status_div">
            <div class="status_title">
                <span>Delivery Bag</span>
                <if condition="$userImg['online_paid'] eq 0">
                    <span class="upload_btn" id="pay_bag">Manage</span>
                <else />
                    <!--span class="material-icons" style="float: right">chevron_right</span-->
                </if>
            </div>
            <if condition="$user['bag_get_type'] eq -1 and $userImg['bag_received'] eq 0 and $userImg['bag_review_desc'] neq ''">
                <div class="status_show" style="color: #ffa52d">
                    <span class="material-icons">info</span>
                    Rejected!
                </div>
                <div class="status_txt">
                    {pigcms{$userImg['bag_review_desc']}
                </div>
            </if>
            <if condition="$userImg['online_paid'] eq 0">
                <if condition="$userImg['bag_review_desc'] eq ''">
                    <div class="status_show" style="color: #ffa52d">
                        <span class="material-icons">info</span>
                        Incomplete!
                    </div>
                    <div class="status_txt">
                        Click “Manage” to purchase a Tutti bag or request to use your own bag.
                    </div>
                </if>
            <else />
                <if condition="$user['bag_get_type'] eq -1 and $userImg['bag_received'] eq 0 and $userImg['bag_review_desc'] eq ''">
                    <div class="status_show" style="color: #72AB29">
                    <span class="material-icons">check_circle_outline</span>
                    Waiting for Review
                    </div>
                </if>
                <if condition="($user['bag_get_type'] eq -1 and $userImg['bag_received'] eq 1) or ($user['bag_get_type'] eq 2 and $userImg['bag_received'] eq 1)">
                    <div class="status_show" style="color: #72AB29">
                    <span class="material-icons">check_circle</span>
                    Approved!
                    </div>
                </if>
                <if condition="$user['bag_get_type'] eq 1 and $userImg['bag_express_num'] neq ''">
                    <div class="status_show" style="color: #72AB29">
                    <span class="material-icons">check_circle</span>
                        Shopped!
                        <br><label style="color: gray;margin-left: 28px;">Tracking# {pigcms{$userImg['bag_express_num']}</label>
                    </div>
                </if>
                <if condition="($user['bag_get_type'] eq 1 and $userImg['bag_express_num'] eq '') or ($user['bag_get_type'] eq 2 and $userImg['bag_received'] eq 0)">
                    <div class="status_show" style="color: #72AB29">
                    <span class="material-icons">check_circle</span>
                    Payment Success!
                    </div>
                </if>

                <if condition="$user['bag_get_type'] eq 2 and $userImg['bag_received'] eq 0 and $city['bag_url_show'] eq 1">
                    <div class="status_txt">
                    Please book a time slot {pigcms{$city['bag_address_url']} to pick up your delivery bag at {pigcms{$city['bag_address']}
                    </div>
                </if>
                <if condition="$user['bag_get_type'] eq 2 and $userImg['bag_received'] eq 0 and $city['bag_url_show'] eq 0">
                    <div class="status_txt">
                    Please pick up your delivery bag at {pigcms{$city['bag_address']}
                    </div>
                </if>
                <if condition="$user['bag_get_type'] eq 1 and $userImg['bag_express_num'] eq ''">
                    <div class="status_txt">
                    We'll ship your delivery bag to you and a tracking number will be available soon.
                    </div>
                </if>
                <if condition="$user['bag_get_type'] eq -1 and $userImg['bag_received'] eq 0 and $userImg['bag_review_desc'] eq ''">
                    <div class="status_txt">
                    Once approved, you can use your own bag to do deliveries!
                    </div>
                </if>
                <if condition="$user['bag_get_type'] eq -1 and $userImg['bag_received'] eq 1">
                    <div class="status_txt">
                    You can use your own bag to do deliveries!
                    </div>
                </if>
            </if>
        </div>
    </div>
    <if condition="$user['group'] eq 1 and $user['reg_status'] eq 5">
        <div class="activate_btn">
            Activate my account!
        </div>
    </if>
</section>
<script>
    $("body").css({"height":$(window).height()});

    $("#upload_doc").click(function () {
        window.parent.location = "{pigcms{:U('Deliver/step_2')}&from=4";
    });
    $("#pay_bag").click(function () {
        window.parent.location = "{pigcms{:U('Deliver/step_3')}&from=4";
    });

    $(".activate_btn").click(function () {
        $.post("{pigcms{:U('Deliver/activate_deliver')}", {}, function (result) {
            if(!result.error_code){
                window.parent.location = "{pigcms{:U('Deliver/index')}";
            }
        }, 'JSON');
    });

    $('#refresh_btn').click(function () {
        if(typeof (window.linkJs) != 'undefined'){
            window.linkJs.reloadWebView();
        }else {
            window.location.reload();
        }
    });
</script>
<!-- Begin INDEED conversion code -->
<script type="text/javascript">
    /* <![CDATA[ */
    var indeed_conversion_id = '3862015109939163';
    var indeed_conversion_label = '';
    /* ]]> */
</script>
<script type="text/javascript" src="https://conv.indeed.com/applyconversion.js">
</script>
<noscript>
    <img height=1 width=1 border=0 src="https://conv.indeed.com/pagead/conv/3862015109939163/?script=0">
</noscript>
<!-- End INDEED conversion code -->
</body>
</html>