<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/shop_amend')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_shop.store_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">{pigcms{:L('_BACK_STORE_NAME_')}</th>
				<td>{pigcms{$now_shop.name}</td>
			</tr>
			<tr>
				<th width="90">{pigcms{:L('_BACK_DIST_TYPE_')}</th>
				<td>
					<select name="delivery_range_type" class="valid">
					<option value="0" <if condition="$now_shop['delivery_range_type'] eq 0">selected</if>>{pigcms{:L('_BACK_RADIUS_')}</option>
					<!--option value="1" <if condition="$now_shop['delivery_range_type'] eq 1">selected</if>>自定义范围</option-->
					</select>
				</td>
			</tr>
			<tr class="delivery_range_type0">
				<th width="90">{pigcms{:L('_BACK_DISTANCE_')}</th>
				<td><input type="text" class="input fl" name="delivery_radius" value="{pigcms{$now_shop.delivery_radius|floatval}" id="reduce_money" size="10" /></td>
			</tr>
			<tr class="delivery_range_type1">
				<td>自定义范围</td>
				<td><input type="button" class="button" value="绘制配送范围" id="baiduMap"/></td>
			</tr>
			<tr class="delivery_range_type1">
			    <input type="hidden" name="delivery_range_polygon" id="delivery_range_polygon" />
				<td colspan="2"><div id="allmap" style="height:350px;"></div></td>
			</tr>
			<tr>
				<th width="90">{pigcms{:L('_BACK_DELIVER_SET_')}</th>
				<td>
					<select name="s_is_open_own" class="valid">
					<option value="0" <if condition="$now_shop['s_is_open_own'] eq 0">selected</if>>{pigcms{:L('_BACK_OFF_')}</option>
					<option value="1" <if condition="$now_shop['s_is_open_own'] eq 1">selected</if>>{pigcms{:L('_BACK_ON_')}</option>
					</select>
				</td>
			</tr>
            <!--
			<tr class="open_own" >
				<th colspan="2" style="color:red">配送时间段一的设置</th>
			</tr>
			<tr class="open_own" >
				<th width="90">免配送费设置</th>
				<td>
					<select name="s_free_type" class="valid" tips="订单金额超过下面的[订单满]免配送费">
					<option value="0" <if condition="$now_shop['s_free_type'] eq 0">selected</if>>免配送费</option>
					<option value="1" <if condition="$now_shop['s_free_type'] eq 1">selected</if>>不免配送费</option>
					<option value="2" <if condition="$now_shop['s_free_type'] eq 2">selected</if>>订单金额达条件免</option>
					</select>
				</td>
			</tr>
			<tr class="open_own free_type full_money">
				<th width="90">订单满</th>
				<td><input type="text" class="input fl" name="s_full_money" value="{pigcms{$now_shop.s_full_money|floatval}" id="reduce_money" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">起步配送费</th>
				<td><input type="text" class="input fl" name="s_delivery_fee" value="{pigcms{$now_shop.s_delivery_fee|floatval}" id="reduce_money" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">起步配送距离</th>
				<td><input type="text" class="input fl" name="s_basic_distance" value="{pigcms{$now_shop.s_basic_distance|floatval}" id="reduce_money" size="10" tips="每单在起步距离（单位:公里）"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">每公里的配送费</th>
				<td><input type="text" class="input fl" name="s_per_km_price" value="{pigcms{$now_shop.s_per_km_price|floatval}" id="reduce_money" size="10" tips="超出起步距离的路程每公里的单价，如果超出部分不是整数的情况下舍去零头取整数，距离是按直线距离算的（单位:元）"/></td>
			</tr>
			<if condition="$is_have_two_time">
			<tr class="open_own" >
				<th colspan="2" style="color:red">配送时间段二的设置</th>
			</tr>
			<tr class="open_own" >
				<th width="90">免配送费设置</th>
				<td>
					<select name="s_free_type2" class="valid" tips="订单金额超过下面的[订单满]免配送费">
					<option value="0" <if condition="$now_shop['s_free_type2'] eq 0">selected</if>>免配送费</option>
					<option value="1" <if condition="$now_shop['s_free_type2'] eq 1">selected</if>>不免配送费</option>
					<option value="2" <if condition="$now_shop['s_free_type2'] eq 2">selected</if>>订单金额达条件免</option>
					</select>
				</td>
			</tr>
			<tr class="open_own free_type2 full_money2">
				<th width="90">订单满</th>
				<td><input type="text" class="input fl" name="s_full_money2" value="{pigcms{$now_shop.s_full_money2|floatval}" id="reduce_money" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">起步配送费</th>
				<td><input type="text" class="input fl" name="s_delivery_fee2" value="{pigcms{$now_shop.s_delivery_fee2|floatval}" id="reduce_money" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">起步配送距离</th>
				<td><input type="text" class="input fl" name="s_basic_distance2" value="{pigcms{$now_shop.s_basic_distance2|floatval}" id="reduce_money" size="10" tips="每单在起步距离（单位:公里）"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">每公里的配送费</th>
				<td><input type="text" class="input fl" name="s_per_km_price2" value="{pigcms{$now_shop.s_per_km_price2|floatval}" id="reduce_money" size="10" tips="超出起步距离的路程每公里的单价，如果超出部分不是整数的情况下舍去零头取整数，距离是按直线距离算的（单位:元）"/></td>
			</tr>
			</if>
            -->
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="http://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<script>
var is_have_two_time = '{pigcms{$is_have_two_time}';
var polygon = '{pigcms{$now_shop['delivery_range_polygon']}';
polygon = $.parseJSON(polygon);
var oldOverlay = [];
$(document).ready(function(){
    var map = new BMap.Map("allmap",{"enableMapClick":false}), point = new BMap.Point('{pigcms{$now_shop["long"]}', '{pigcms{$now_shop["lat"]}');
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom();
    var marker = new BMap.Marker(point);// 创建标注
    map.addOverlay(marker);
    marker.enableDragging();
    if (polygon != null) {
    	for (var i in polygon) {
    		var polygonArr = [];
    		var lat_lng = [];
    		for (var ii in polygon[i]) {
    			polygonArr.push(new BMap.Point(polygon[i][ii].lng, polygon[i][ii].lat));
    			lat_lng.push(polygon[i][ii].lat + '-' + polygon[i][ii].lng);
    		}
    		$('#delivery_range_polygon').val(lat_lng.join('|'));
    		
    		var poly = new BMap.Polygon(polygonArr, {strokeColor:"red", fillColor:"red", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8});

    		map.addOverlay(poly);  //创建多边形
    		oldOverlay.push(poly)
    		console.log(oldOverlay);
    	}
    }
    
    var overlays = [];
    var overlaycomplete = function(e){
        overlays.push(e.overlay);
        var latLng = e.overlay.getPath();
        var lat_lng = [];
        for (var i in latLng) {
        	lat_lng.push(latLng[i].lat + '-' + latLng[i].lng);
        }
        $('#delivery_range_polygon').val(lat_lng.join('|'));
    };
    var styleOptions = {
        strokeColor:"red",    //边线颜色。
        fillColor:"red",      //填充颜色。当参数为空时，圆形将没有填充效果。
        strokeWeight: 2,       //边线的宽度，以像素为单位。
        strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
        fillOpacity: 0.2,      //填充的透明度，取值范围0 - 1。
        strokeStyle: 'solid' //边线的样式，solid或dashed。
    }
    //实例化鼠标绘制工具
    var drawingManager = new BMapLib.DrawingManager(map, {
        isOpen: false, //是否开启绘制模式
        enableDrawingTool: false, //是否显示工具栏
        drawingMode:BMAP_DRAWING_POLYGON,
        drawingToolOptions: {
            anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
            offset: new BMap.Size(5, 5), //偏离值
        },
        circleOptions: styleOptions, //圆的样式
        polylineOptions: styleOptions, //线的样式
        polygonOptions: styleOptions, //多边形的样式
        rectangleOptions: styleOptions //矩形的样式
    });


    $('#baiduMap').click(function(){
        drawingManager.open();
        for(var i = 0; i < overlays.length; i++){
            map.removeOverlay(overlays[i]);
        }
        if (oldOverlay.length > 0) {
            console.log(oldOverlay);
        	for(var i = 0; i < oldOverlay.length; i++){
                map.removeOverlay(oldOverlay[i]);
            }
        }
        overlays = [];
    });

    //添加鼠标绘制工具监听事件，用于获取绘制结果
    drawingManager.addEventListener('overlaycomplete', overlaycomplete);

    var delivery_range_type = $('select[name=delivery_range_type]').val();
	if (delivery_range_type == 0) {
		$('.delivery_range_type0').show();
		$('.delivery_range_type1').hide();
	} else {
		$('.delivery_range_type1').show();
		$('.delivery_range_type0').hide();
	}
	$('select[name=delivery_range_type]').change(function(){
	    if ($(this).val() == 0) {
			$('.delivery_range_type0').show();
			$('.delivery_range_type1').hide();
		} else {
			$('.delivery_range_type1').show();
			$('.delivery_range_type0').hide();
		}
	});
	var s_is_open_own = $('select[name=s_is_open_own]').val(), s_free_type = $('select[name=s_free_type]').val();
	if (s_is_open_own == 1) {
		$('.open_own').show();
		if (s_free_type == 0) {
			$('.free_type').hide();
		} else if (s_free_type == 1) {
			$('.free_type').show();
			$('.full_money').hide();
		} else if (s_free_type == 2) {
			$('.free_type').show();
		}
		<if condition="$is_have_two_time">
		var s_free_type2 = $('select[name=s_free_type2]').val();
		if (s_free_type2 == 0) {
			$('.free_type2').hide();
		} else if (s_free_type2 == 1) {
			$('.free_type2').show();
			$('.full_money2').hide();
		} else if (s_free_type2 == 2) {
			$('.free_type2').show();
		}
		</if>
	} else {
		$('.open_own').hide();
	}
	$('select[name=s_is_open_own]').change(function(){
		if ($(this).val() == 1) {
			$('.open_own').show();
			s_free_type = $('select[name=s_free_type]').val();
			if (s_free_type == 0) {
				$('.free_type').hide();
			} else if (s_free_type == 1) {
				$('.free_type').show();
				$('.full_money').hide();
			} else if (s_free_type == 2) {
				$('.free_type').show();
			}
			<if condition="$is_have_two_time">
				s_free_type2 = $('select[name=s_free_type2]').val();
				if (s_free_type2 == 0) {
					$('.free_type2').hide();
				} else if (s_free_type2 == 1) {
					$('.free_type2').show();
					$('.full_money2').hide();
				} else if (s_free_type2 == 2) {
					$('.free_type2').show();
				}
			</if>
		} else {
			$('.open_own').hide();
		}
	});
	$('select[name=s_free_type]').change(function(){
		if ($(this).val() == 0) {
			$('.free_type').hide();
		} else if ($(this).val() == 1) {
			$('.free_type').show();
			$('.full_money').hide();
		} else if ($(this).val() == 2) {
			$('.free_type').show();
		}
	});
	
	$('select[name=s_free_type2]').change(function(){
		if ($(this).val() == 0) {
			$('.free_type2').hide();
		} else if ($(this).val() == 1) {
			$('.free_type2').show();
			$('.full_money2').hide();
		} else if ($(this).val() == 2) {
			$('.free_type2').show();
		}
	});
});
</script>
<include file="Public:footer"/>