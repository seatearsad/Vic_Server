<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">配送员管理</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','添加配送员',680,560,true,false,false,editbtn,'edit',true);">添加配送员</a>
					<a href="{pigcms{:U('Config/index',array('galias'=>'deliver','header'=>'Deliver/header'))}">配送配置</a>
                    <a href="{pigcms{:U('Deliver/map')}" class="on">配送员监控</a>
				</ul>
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
        <span class="d_status">{pigcms{$deliver.order_count} 单配送中</span>
        <span class="d_lat">{pigcms{$deliver.lat}</span>
        <span class="d_lng">{pigcms{$deliver.lng}</span>
        <span class="d_phone">{pigcms{$deliver.phone}</span>
    </li>
    </volist>
</ul>
<div id="e_call">紧急召唤</div>
<include file="Public:footer"/>
<style>
    #deliver_map{
        width: 98%;
        border: 1px #999999 solid;
        height: 550px;
    }
    #e_call{
        position: absolute;
        width: 60px;
        height: 30px;
        text-align: center;
        background-color: #0a51b9;
        cursor: pointer;
        color: #FFFFFF;
        top:70px;
        right: 50px;
        line-height: 30px;
        font-weight: bold;
        border: 1px #cccccc solid;
    }

    #deliver_list{
        position: absolute;
        width: 300px;
        border: 1px #cccccc solid;
        left:20px;
        top:70px;
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
    var mapOptions = {
        zoom: 18,
        center: {lat:48.4245911, lng:-123.3667908}
    }

    var map = new google.maps.Map(document.getElementById('deliver_map'), mapOptions);

    var myLatlng = new google.maps.LatLng(48.4245911,-123.3667908);

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
        $.post("{pigcms{:U('Deliver/e_call')}", {}, function(result) {
            if(result){
                message = result.msg;
            }else {
                message = 'Error';
            }

            alert(message);
        },'json');
    });
</script>
