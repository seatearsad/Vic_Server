<include file="Public:header"/>
<div id="wrapper">

    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('_BACK_COURIER_MONI_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_COURIER_MONI_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-6" style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">
                <a href="{pigcms{:U('Shop/order')}" class="button" style="float:right;margin-right: 10px;"><button class="btn btn-white text-grey">{pigcms{:L('_BACK_ORDER_LIST_')}</button></a>
                <a href="{pigcms{:U('Deliver/deliverList')}" class="button" style="float:right;margin-right: 10px;"><button class="btn btn-white text-grey">{pigcms{:L('_BACK_DELIVERY_LIST_')}</button></a>
                <!--if condition="$system_session['level'] eq 2">
                    <a href="{pigcms{:U('Deliver/user')}" style="float:right;">
                        <button class="btn btn-white  text-grey">{pigcms{:L('_BACK_COURIER_MANA_')}</button>
                    </a>
                    <if condition="$system_session['level'] neq 3">
                        <a href="{pigcms{:U('Deliver/rule')}">
                            <button class="btn btn-white text-grey ">{pigcms{:L('D_DELIVERYFEE_SETTING')}</button>
                        </a>
                    </if>
                    <a href="{pigcms{:U('Deliver/map')}">
                        <button class="btn btn-white text-grey active">{pigcms{:L('_BACK_COURIER_MONI_')}</button>
                    </a>
                    <a href="{pigcms{:U('Deliver/schedule')}">
                        <button class="btn btn-white text-grey">{pigcms{:L('_DELIVER_SCHEDULE_')}</button>
                    </a>
                </if>
                <a href="javascript:void(0);"
                   onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','{pigcms{:L(\'_BACK_ADD_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);"
                   style="float:right;margin-left: 10px;">
                    <button class="btn btn-primary">{pigcms{:L('_BACK_ADD_COURIER_')}</button>
                </a-->

            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('_BACK_ORDER_LIST_')}</h5>
                        <div class="ibox-tools">
                            <if condition="$system_session['level'] neq 3">
                                <div style="margin-left:40px;">

                                </div>
                            </if>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!-------------------------------- 工具条 -------------------------------------->
                        <div style="height: 50px;">
                            <form action="{pigcms{:U('Merchant/index')}" class="form-inline" role="form"
                                  method="get">
                                <div id="tool_bar" style="form-group tutti_toolbar" style="height: 80px;">
                                    City:
                                    <select name="searchtype" id="city_select" class="form-control">
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <if condition="$system_session['level'] neq 3">
                                        <if condition="$curr_city['urgent_time'] neq 0">
                                            <div id="send_sms">Send SMS</div>
                                            <div id="send_notify">Send Notify</div>
                                        </if>
                                    </if>
                                </div>
                            </form>
                        </div>
                        <!------------------------------------------------------------------------------>
                        <!-- <form name="myform" id="myform" action="" method="post">-->
                        <div id="deliver_map">

                        </div>

                    </div>
                    <ul id="deliver_list">
                        <li style="font-weight: bold;background-color:#ced5e2">
                            <span class="d_id">ID</span>
                            <span class="d_name">Name</span>
                            <span class="d_status">Status</span>
                        </li>
                        <volist name="list" id="deliver">
                            <li class="d_memo" title="Phone:{pigcms{$deliver.phone}">
                                <span class="d_id">{pigcms{$deliver.uid}</span>
                                <span class="d_name">{pigcms{$deliver.name}</span>
                                <span class="d_status">{pigcms{$deliver.order_count}{pigcms{:L('_BACK_ORDER_DELIVERY_')}</span>
                                <span class="d_lat">{pigcms{$deliver.lat}</span>
                                <span class="d_lng">{pigcms{$deliver.lng}</span>
                                <span class="d_phone">{pigcms{$deliver.phone}</span>
                            </li>
                        </volist>
                    </ul>
                    <if condition="$system_session['level'] neq 3">
                        <if condition="$curr_city['urgent_time'] eq 0">
                            <div id="e_call">{pigcms{:L('_BACK_HAND_ALERT_')}</div>
                            <else />
                            <div id="r_e_call">{pigcms{:L('_BACK_HAND_ALERT_')}</div>
                        </if>
                    </if>
                </div>
            </div>
        </div>
    </div>
    <style>
        #deliver_map{
            width: 98%;
            border: 1px #999999 solid;
            height: 550px;
        }

        #send_sms,#send_notify{
            width: 180px;
            height: 30px;
            text-align: center;
            background-color: #ffa52d;
            cursor: pointer;
            color: #FFFFFF;
            float: right;
            line-height: 30px;
            font-weight: bold;
            border: 1px #cccccc solid;
            margin-right: 20px;
        }
        #e_call,#r_e_call{
            position: absolute;
            width: 180px;
            height: 30px;
            text-align: center;
            background-color: #0a51b9;
            cursor: pointer;
            color: #FFFFFF;
            top:130px;
            right:80px;
            line-height: 30px;
            font-weight: bold;
            border: 1px #cccccc solid;
        }
        #r_e_call{
            background-color: #999999;
        }

        #deliver_list{
            position: absolute;
            width: 300px;
            border: 1px #cccccc solid;
            left:50px;
            top:130px;
            border-bottom: 0px;
            background-color: #ffffff;
        }
        #deliver_list li{
            width: 100%;
            height: 24px;
            line-height: 24px;
            text-align: center;
            border-bottom: 1px #cccccc solid;
        }
        #deliver_list li.d_memo{
            cursor: pointer;
        }
        #deliver_list li.d_memo:hover{
            background-color: #F0ECEB;
            cursor: pointer;
        }
        .curr{
            background-color: #F0ECEB;
        }
        .d_id{
            float: left;
            width: 49px;
            height: 24px;
            border-right: 1px #cccccc solid;
        }
        .d_name{
            float: left;
            height: 24px;
            width:149px;
            border-right: 1px #cccccc solid;
        }
        .d_status{
            float: left;
            height: 24px;
            width:100px;
        }
        .d_lat,.d_lng,.d_phone{
            display: none;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language={pigcms{:C('DEFAULT_LANG')}"></script>
    <script type="text/javascript">
        var city_id = $('#city_select').val();
        $('#city_select').change(function () {
            city_id = $(this).val();
            window.location.href = "{pigcms{:U('Deliver/map')}" + "&city_id="+city_id;
        });

        var init_lat = $('body').find('.d_memo').first().children('.d_lat').text();
        var init_lng = $('body').find('.d_memo').first().children('.d_lng').text();

        if(init_lat == '' || init_lng == ''){
            init_lat = 48.4245911;
            init_lng = -123.3667908;
        }
        var mapOptions = {
            zoom: 18,
            center: {lat:parseFloat(init_lat), lng:parseFloat(init_lng)}
        }

        var map = new google.maps.Map(document.getElementById('deliver_map'), mapOptions);

        //var myLatlng = new google.maps.LatLng(48.4245911,-123.3667908);

        var markers = [];

        loadAllMarker();

        function loadAllMarker() {
            $(".d_memo").each(function(){
                // alert($(this).text())
                var lat = $(this).children('.d_lat').text();
                var lng = $(this).children('.d_lng').text();

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat,lng),
                    map: map,
                    label:$(this).children('.d_id').text(),
                    title: $(this).children('.d_name').text() + '('+ $(this).children('.d_phone').text()+ '):' + $(this).children('.d_status').text(),
                    tag:$(this).children('.d_id').text()
                });
                marker.addListener('click', function() {
                    map.setCenter(marker.getPosition());
                    showDeliver(marker.tag);
                });
                marker.addListener('mouseover', function() {
                    showDeliver(marker.tag);
                });
                marker.addListener('mouseout', function() {
                    showDeliver(-1);
                });
                markers.push(marker);
            });
        }
        function showDeliver(d_id) {
            $(".d_memo").each(function(){
                if(d_id == $(this).children('.d_id').text())
                    $(this).addClass('curr');
                else
                    $(this).removeClass('curr');
            });
        }

        $('.d_memo').mouseover(function () {
            //alert($(this).children('.d_name').text());
            for (var i = 0; i < markers.length; i++) {
                if(markers[i].tag == $(this).children('.d_id').text()) {
                    markers[i].setMap(map);
                    markers[i].setAnimation(google.maps.Animation.BOUNCE);
                }else
                    markers[i].setMap(null);
            }
        });

        $('.d_memo').mouseout(function () {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
                markers[i].setAnimation(null);
            }
        });

        $('.d_memo').click(function () {
            for (var i = 0; i < markers.length; i++) {
                if(markers[i].tag == $(this).children('.d_id').text())
                    map.setCenter(markers[i].getPosition());
            }

        });

        $('#e_call').click(function () {
            $.post("{pigcms{:U('Deliver/e_call')}", {'city_id':city_id}, function(result) {
                if(result){
                    message = result.msg;
                }else {
                    message = 'Error';
                }

                alert(message);
                window.location.reload();
            },'json');
        });

        $('#send_sms').click(function () {
            $.post("{pigcms{:U('Deliver/urgent_send')}", {'city_id':city_id,'type':'0'}, function(result) {
                if(result){
                    message = result.msg;
                }else {
                    message = 'Error';
                }

                alert(message);
            },'json');
        });

        $('#send_notify').click(function () {
            $.post("{pigcms{:U('Deliver/urgent_send')}", {'city_id':city_id,'type':'1'}, function(result) {
                if(result){
                    message = result.msg;
                }else {
                    message = 'Error';
                }

                alert(message);
            },'json');
        });

        var urgent_time = parseInt("{pigcms{$curr_city['urgent_time']}");
        var curr_time = 0;
        var show_time = 0;
        if(urgent_time != 0){
            curr_time = parseInt("{pigcms{:time()}");
            show_time = 7200 - (curr_time - urgent_time);
        }
        show_time_func();
        function show_time_func() {
            if(urgent_time != 0){
                if(show_time == 0){
                    window.location.reload();
                }else {
                    var h = parseInt(show_time / 3600);
                    var i = parseInt((show_time - 3600 * h) / 60);
                    var s = (show_time - 3600 * h) % 60;
                    if (s < 10)
                        s = '0' + s;

                    var time_str = h + ':' + i + ':' + s;

                    $('#r_e_call').html(time_str);

                    show_time -= 1;

                    window.setTimeout(function () {
                        show_time_func()
                    }, 1000);
                }
            }
        }

    </script>
    <include file="Public:footer"/>
