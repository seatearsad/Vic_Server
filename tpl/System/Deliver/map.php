<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">{pigcms{:L('_BACK_COURIER_MANA_')}</a>
                    <if condition="$system_session['level'] neq 3">
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','添加配送员',680,560,true,false,false,editbtn,'edit',true);">添加配送员</a>
                    <!--a href="{pigcms{:U('Config/index',array('galias'=>'deliver','header'=>'Deliver/header'))}">配送配置</a-->
                    <a href="{pigcms{:U('Deliver/rule')}">配送配置</a>
                    </if>
                    <a href="{pigcms{:U('Deliver/map')}" class="on">{pigcms{:L('_BACK_COURIER_MONI_')}</a>
                    <a href="{pigcms{:U('Deliver/schedule')}">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
				</ul>
			</div>
            <div style="margin: 10px 0;">
                City:
                <select name="searchtype" id="city_select">
                    <volist name="city" id="vo">
                        <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                    </volist>
                </select>
                <if condition="$curr_city['urgent_time'] neq 0">
                    <div id="send_sms">Send SMS</div>
                </if>
            </div>
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
<if condition="$curr_city['urgent_time'] eq 0">
    <div id="e_call">{pigcms{:L('_BACK_HAND_ALERT_')}</div>
<else />
    <div id="r_e_call">{pigcms{:L('_BACK_HAND_ALERT_')}</div>
</if>
<include file="Public:footer"/>
<style>
    #deliver_map{
        width: 98%;
        border: 1px #999999 solid;
        height: 550px;
    }

    #send_sms{
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
        top:120px;
        right: 50px;
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
        left:20px;
        top:120px;
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language={pigcms{:C('DEFAULT_LANG')}"></script>
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
        $.post("{pigcms{:U('Deliver/urgent_send')}", {'city_id':city_id}, function(result) {
            if(result){
                message = result.msg;
            }else {
                message = 'Error';
            }

            alert(message);
        },'json');
    });
</script>
