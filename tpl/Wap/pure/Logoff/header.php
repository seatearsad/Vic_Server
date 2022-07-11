<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<style>
    body{
        max-width: 100%;
        background-color: #F8F8F8;
        color: #555555;
        position: unset;
    }
    #tutti_header{
        width: 100%;
        height: 60px;
        display: flex;
        background-color: #ffffff;
        position: fixed;
        z-index: 999;
        left: 0;
        top:0;
    }
    #header_menu{
        display: flex;
        flex: 1 1 100%;
    }
    #header_sign{
        flex: 1 1 20%;
    }

    #header_sign a{
        margin-right: 5%;
        margin-top: 11px;
        border-radius: 3px;
    }
    .material-icons{
        width: 35px;
    }
    .refresh{
        height: 48px;
        width: 48px;
        cursor: pointer;
        margin-left: 5%;
        margin-top: 10px;
        padding-top: 10px;
        box-sizing: border-box;
        background-color: white;
        border-radius: 24px;
        position: relative;
    }
    .header_title{
        flex: 1 1 100%;
        text-align: center;
        line-height: 60px;
        font-size: 18px;
        font-weight: bold;
    }
    .content_div{
        width: 85%;
        margin: 100px auto 0 auto;
    }
    .content_title{
        font-size: 18px;
        font-weight: bold;
    }
    .content_desc{
        margin-top: 10px;
    }
    input{
        width: 100%;
        border-radius: 12px;
        border: 1px solid #EEEEEE;
        background-color: white;
        height: 40px;
        text-indent: 10px;
        font-size: 14px;
        color: #666666;
    }
    .bottom_btn{
        width: 86%;
        background-color: #294068;
        color: white;
        font-size: 20px;
        font-weight: bold;
        line-height: 50px;
        border-radius: 10px;
        position: absolute;
        bottom: 30px;
        left:7%;
        text-align: center;
        cursor: pointer;
    }
</style>
<div id="tutti_header">
    <div id="header_menu">
        <div class="refresh" id="back_btn">
            <span class="material-icons" style="font-size: 30px;">arrow_back</span>
        </div>
        <div class="header_title">
            {pigcms{$page_title}
        </div>
        <div id="header_sign"></div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script>
    $('#back_btn').click(function () {
        window.history.go(-1);
    });

    function loading() {
        layer.open({
            type:2,
            content:'Loading...'
        });
    }
    function showMessage(message) {
        layer.open({
            content:message,
            btn: ['Confirm'],
        });
    }
    function closeMessage() {
        layer.closeAll();
    }
</script>