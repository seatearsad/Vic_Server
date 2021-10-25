$(function(){
    $(".delivery p em").each(function(){
       $(this).width($(window).width() - $(this).siblings("i").width() -55) 
    });
    var mark = 0;
    $(document).on('click', '.rob', function(e){
		if (mark) {
			return false;
		}
		mark = 1;
		//e.stopPropagation();
		var supply_id = $(this).attr("data-spid");
		$.post(location_url, "supply_id="+supply_id, function(json){
			mark = 0;
			if (json.status) {
                layer.open({title:['Tips','background-color:#ffa52d;color:#fff;'], time:1, content:json.info,end:function(){}});
			} else {
				layer.open({title:[' ','background-color:#ffa52d;color:#fff;'],content:json.info,btn: ['Confirm'],end:function(){}});
			}
			$(".supply_"+supply_id).remove();
		});
    });

    $(document).on('click', '.rej', function(e){
        if (mark) {
            return false;
        }
        mark = 1;

        e.stopPropagation();
        var supply_id = $(this).attr("data-spid");
        //alert(supply_id);
        $.post(reject_url, "supply_id="+supply_id, function(json){
            mark = 0;
            if (json.status) {
                layer.open({title:['Tips','background-color:#ffa52d;color:#fff;'], time:1, content:json.info,end:function(){}});
            } else {
                layer.open({title:[' ','background-color:#ffa52d;color:#fff;'],content:json.info,btn: ['Confirm'],end:function(){}});
            }
            $(".supply_"+supply_id).remove();
            updateNum();
        });
    });
	getList();
	var timer = setInterval(getList, 2000);
	
	$(document).on("click", '.go_detail', function(e){
		//e.stopPropagation();
		//先关闭查看订单详情
		//location.href = detail_url + '&supply_id=' + $(this).attr("data-id");
	});
});
function getList() {
    var ua = navigator.userAgent;
    if(!ua.match(/TuttiDeliver/i)) {
		navigator.geolocation.getCurrentPosition(function (position) {
			console.log(position);
			list_detail(position.coords.latitude, position.coords.longitude);
		},function(error) {
			if(error)
				list_detail(lat,lng);
		});
		return false;
    }

	
	/*var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
//			lat = r.point.lat;
//			lng = r.point.lng;
			list_detail(r.point.lat, r.point.lng);
//			console.log(lat + '--------->lng:' + lng);
//			map.panTo(r.point);
//			var mk = new BMap.Marker(r.point);
//			map.addOverlay(mk);
//			mk.setAnimation(BMAP_ANIMATION_BOUNCE); 
//			alert('您的位置：'+r.point.lng+','+r.point.lat);
		} else {
			list_detail(lat, lng);
//			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true})*/
	//return false;
	console.log(lat + '--------->lng:' + lng);
	$.get(location_url, {'lat':lat, 'lng':lng}, function(result){
		if (result.err_code) {
			$('.psnone').show();
            $('#container').html('<p style="text-align: center;width: 90%;margin: auto;">No pending orders. Please wait for the next available order.</p>');
			return false;
		}
		$('.psnone').hide();
		laytpl($('#replyListBoxTpl').html()).render(result, function (html) {
			$('#container').html(html);
			$(".delivery p em").each(function () {
				$(this).width($(window).width() - $(this).siblings("i").width() - 55)
			});
		});
	}, 'json');
}


function list_detail(lat, lng)
{
	$.get(location_url, {'lat':lat, 'lng':lng}, function(result){
		if (result.err_code) {
			//$('#container').html('<div class="psnone" ><img src="' + static_path + 'images/qdz_02.jpg"></div>');
            $('#container').html('<p style="text-align: center;width: 90%;margin: auto;">No pending orders. Please wait for the next available order.</p>');
			return false;
		}
		laytpl($('#replyListBoxTpl').html()).render(result, function(html){
			$('#container').html(html);
		    $(".delivery p em").each(function(){
		        $(this).width($(window).width() - $(this).siblings("i").width() -55) 
	    	});
		});
	}, 'json');
}

function updateNum() {
    $.get(update_url, function(response){
        if (response.err_code == false) {
            $('#gray_count').html(response.gray_count);
            $('#deliver_count').html(response.deliver_count);
            $('#finish_count').html(response.finish_count);
        }
    }, 'json');
}