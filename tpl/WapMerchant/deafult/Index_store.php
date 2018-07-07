<include file="Public:top"/>
<body>
<link rel="stylesheet" href="{pigcms{$static_path}css/shop.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/shop_info.css">
<style>
	.top-img-container{
		padding-bottom: 0;
		text-align: left;
		border-bottom: 1px solid #ddd;
	}
	.top-img-container .pigcms-form-title{
		float: left;
		width: auto;
		line-height: 60px;
	}
	.up-load-img{
		float:right;
		width: 60px;
		height: 60px;
		margin:0 4% 0 0;
		text-align: center;
	}
	.up-load-img img{
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
	#big-pic{
		width: 110px;
		height: 50px;
		border-radius: 0!important;
	}
	#big-pic img{border-radius: 0!important;}
	.form_tips{color:red;}
	.radio{margin: 0 3%;padding: 5px 0; width: 92%;line-height: 20px!important;background-color: #FFF;}
	#choose_area{margin-right: 30px;width: 35%;padding-left: 20px;}
	#choose_circle{width: 45%;padding-left: 20px;}
</style>
<header class="pigcms-header mm-slideout">
	<a href="#slide_menu" id="pigcms-header-left"><i class="iconfont icon-menu "></i></a>
	<p id="pigcms-header-title">店铺管理</p>
</header>
<div class="container container-fill-" >
	<!--左侧菜单-->
	<include file="Public:leftMenu"/>
	<!--左侧菜单结束-->
	<form class="pigcms-form" method="post" action="" onsubmit="return checkSubmit();">
		<div class="pigcms-container"></div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>店铺名称</p>
			<input type="text" class="pigcms-input-block" name="name" placeholder="请输入店铺名称" value="{pigcms{$store['name']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>是否设置成主店&nbsp;&nbsp;&nbsp;<span class="form_tips">如果将此店铺设置成主店，系统将自动取消其他已设置的主店</span></p>
			<div class="radio">
				<label>
					<input type="radio" name="ismain" value="1" <if condition="$store['ismain'] eq 1">checked="checked"</if>>
					<span class="lbl" style="z-index: 1">是</span>
				</label>&nbsp;&nbsp;&nbsp;
				<label>
					<input type="radio" name="ismain" value="0" <if condition="$store['ismain'] neq 1">checked="checked"</if>>
					<span class="lbl" style="z-index: 1">否</span>
				</label>
			</div>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>联系电话</p>
			<input type="tel" class="pigcms-input-block" name="phone" placeholder="请输入店铺联系电话" value="{pigcms{$store['phone']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>联系微信</p>
			<input type="text" class="pigcms-input-block" name="weixin" placeholder="请输入店铺联系微信" value="{pigcms{$store['weixin']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>联系Q Q</p>
			<input type="text" class="pigcms-input-block" name="qq" placeholder="请输入店铺联系QQ" value="{pigcms{$store['qq']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'> 关键词</p>
			<input type="text" class="pigcms-input-block" name="keywords" placeholder="请输入关键词" value="{pigcms{$store['keywords']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'> 人均消费</p>
			<input type="tel" class="pigcms-input-block" name="permoney" placeholder="请输入人均消费额" value="{pigcms{$store['permoney']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>店铺特色</p>
			<input type="text" class="pigcms-input-block" name="feature" placeholder="请输入店铺特色" value="{pigcms{$store['feature']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>店铺所在地</p>
			<div id="choose_cityarea" style="margin-left: 18px;" province_id="{pigcms{$store.province_id}" city_id="{pigcms{$store.city_id}" area_id="{pigcms{$store.area_id}" circle_id="{pigcms{$store.circle_id}"></div>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>详细地址</p>
			<input type="text" class="pigcms-input-block" name="adress" placeholder="请输入店铺详细地址" value="{pigcms{$store['adress']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'><label for="trafficroute">交通路线</label>&nbsp;&nbsp;&nbsp;<span class="form_tips">简单描述本店交通路线80字以内</span></p>
			<input class="pigcms-input-block" name="trafficroute" id="trafficroute" type="text" value="{pigcms{$store.trafficroute}"/>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'><label for="sort">店铺排序</label>&nbsp;&nbsp;&nbsp;<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span></p>
			<input class="pigcms-input-block" size="10" name="sort" id="sort" type="text" value="{pigcms{$store.sort}" />
		</div>
		<if condition="$config['store_open_meal']">
		<div class="pigcms-container">
			<p class='pigcms-form-title'>{pigcms{$config.meal_alias_name}</p>
			<select name="have_meal" id="have_meal" class="pigcms-input-block">
				<option value="0" <if condition="$store['have_meal'] eq 0">selected="selected"</if>>关闭</option>
				<option value="1" <if condition="$store['have_meal'] eq 1">selected="selected"</if>>开启</option>
			</select>
		</div>
		</if>
		<if condition="$config['store_open_meal']">
		<!--div class="pigcms-container">
		<p class='pigcms-form-title'>{pigcms{$config.meal_alias_name}类型&nbsp;&nbsp;&nbsp;<span class="form_tips">【其他】是指（外卖，超市，花店...）</span></p>
		<select name="store_type" id="store_type" class="pigcms-input-block">
			<option value="0" <if condition="$store['store_type'] eq 0">selected="selected"</if>>订餐和外卖</option>
			<option value="1" <if condition="$store['store_type'] eq 1">selected="selected"</if>>订餐</option>
			<option value="2" <if condition="$store['store_type'] eq 2">selected="selected"</if>>其他</option>
		</select>
		</div-->
		</if>
		<if condition="$config['store_open_group']">
		<div class="pigcms-container">
			<p class='pigcms-form-title'>{pigcms{$config.group_alias_name}</p>
			<select name="have_group" id="have_group" class="pigcms-input-block">
				<option value="0" <if condition="$store['have_group'] eq 0">selected="selected"</if>>关闭</option>
				<option value="1" <if condition="$store['have_group'] eq 1">selected="selected"</if>>开启</option>
			</select>
		</div>
		</if>


        <div class="pigcms-container" style="position:relative;">
            <p class="pigcms-form-title">营业时间&nbsp;&nbsp;&nbsp;<span class="form_tips">周一至周日各设置三个时间段</span></p>
        </div>
        <!-- 周一-->
		<div class="pigcms-container" style="position:relative;height:64px">
            <p class="pigcms-form-title">周一</p>
			<select name="open_1" class="pigcms-input"></select>
			<span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
			<select name="close_1" class="pigcms-input"></select>
		</div>
		<div class="pigcms-container" style="position:relative;height:42px">
			<select name="open_2" class="pigcms-input"></select>
			<span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
			<select name="close_2" class="pigcms-input"></select>
		</div>

		<div class="pigcms-container" style="position:relative;height:64px">
			<select name="open_3" class="pigcms-input"></select>
			<span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
			<select name="close_3" class="pigcms-input"></select>
		</div>
        <!-- /周一-->
        <!-- 周二-->
        <div class="pigcms-container" style="position:relative;height:64px">
            <p class="pigcms-form-title">周二</p>
            <select name="open_4" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_4" class="pigcms-input"></select>
        </div>
        <div class="pigcms-container" style="position:relative;height:42px">
            <select name="open_5" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_5" class="pigcms-input"></select>
        </div>
        <div class="pigcms-container" style="position:relative;height:64px">
            <select name="open_6" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_6" class="pigcms-input"></select>
        </div>
        <!-- /周二-->
        <!-- 周三-->
        <div class="pigcms-container" style="position:relative;height:64px">
            <p class="pigcms-form-title">周三</p>
            <select name="open_7" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_7" class="pigcms-input"></select>
        </div>
        <div class="pigcms-container" style="position:relative;height:42px">
            <select name="open_8" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_8" class="pigcms-input"></select>
        </div>

        <div class="pigcms-container" style="position:relative;height:64px">
            <select name="open_9" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_9" class="pigcms-input"></select>
        </div>
        <!-- /周三-->
        <!-- 周四-->
        <div class="pigcms-container" style="position:relative;height:64px">
            <p class="pigcms-form-title">周四</p>
            <select name="open_10" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_10" class="pigcms-input"></select>
        </div>
        <div class="pigcms-container" style="position:relative;height:42px">
            <select name="open_11" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_11" class="pigcms-input"></select>
        </div>

        <div class="pigcms-container" style="position:relative;height:64px">
            <select name="open_12" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_12" class="pigcms-input"></select>
        </div>
        <!-- /周四-->
        <!-- 周五-->
        <div class="pigcms-container" style="position:relative;height:64px">
            <p class="pigcms-form-title">周五</p>
            <select name="open_13" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_13" class="pigcms-input"></select>
        </div>
        <div class="pigcms-container" style="position:relative;height:42px">
            <select name="open_14" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_14" class="pigcms-input"></select>
        </div>

        <div class="pigcms-container" style="position:relative;height:64px">
            <select name="open_15" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_15" class="pigcms-input"></select>
        </div>
        <!-- /周五-->
        <!-- 周六-->
        <div class="pigcms-container" style="position:relative;height:64px">
            <p class="pigcms-form-title">周六</p>
            <select name="open_16" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_16" class="pigcms-input"></select>
        </div>
        <div class="pigcms-container" style="position:relative;height:42px">
            <select name="open_17" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_17" class="pigcms-input"></select>
        </div>

        <div class="pigcms-container" style="position:relative;height:64px">
            <select name="open_18" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_18" class="pigcms-input"></select>
        </div>
        <!-- /周六-->
        <!-- 周天-->
        <div class="pigcms-container" style="position:relative;height:64px">
            <p class="pigcms-form-title">周日</p>
            <select name="open_19" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_19" class="pigcms-input"></select>
        </div>
        <div class="pigcms-container" style="position:relative;height:42px">
            <select name="open_20" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_20" class="pigcms-input"></select>
        </div>

        <div class="pigcms-container" style="position:relative;height:64px">
            <select name="open_21" class="pigcms-input"></select>
            <span style="position:absolute;left:50%;margin-left:-7px;line-height:40px;font-size:14px;">至</span>
            <select name="close_21" class="pigcms-input"></select>
        </div>
        <!-- /周天-->





        <div class="pigcms-container" >
			<p class='pigcms-form-title'>地图位置</p>
			<div id="location" onclick="open_map()">
				<i class="iconfont icon-location"></i>
				<span>{pigcms{$store['adress']|default='点击设置店铺地图位置'}</span>
			</div>
			<input type="hidden" name='lat' id='lat' value="{pigcms{$store['lat']}">
			<input type="hidden" name='long' id='long' value="{pigcms{$store['long']}">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>店铺简介</p>
			<textarea class="pigcms-textarea" name="txt_info" id="txt_info" cols="20" rows="3" placeholder="" >{pigcms{$store['txt_info']}</textarea>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>店铺图片</p>
			<div class="pic-detail-container">
				<div class="detail-img" id='detail-img-add' onclick="upLoadDetailImg()">
					<i class="iconfont icon-upload"></i>
					<p>添加图片</p>
				</div>
				<input type="hidden" name='pic_detail'>
				<input type="hidden" name='store_id' value="{pigcms{$store['store_id']}">
				<div class="clearfix"></div>
			</div>
		</div>
		<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit">保存</button>
	</form>
</div>
<div id="map-layer">
	<div id="map-header">
		<div id="map-cancel">关闭</div>
		<div id="map-title">标注位置</div>
		<div id="map-confirm">确定</div>
	</div>
	<div id="map" style="height:100%;"></div>
</div>
<script src="{pigcms{$static_path}js/wgs2mars.min.js"></script>
<script src="{pigcms{$static_path}js/shop_info_location.js"></script>
<script>
	$('#map').height($(window).height()-60);
	var picarr = [{pigcms{$store['picstr']}];
	var container = 'browser',
		lat = $("[name='lat']").val() ? $("[name='lat']").val() : '',
		lng = $("[name='long']").val() ? $("[name='long']").val() : '',
// 		center = new qq.maps.LatLng(lat, lng),
// 		shop_latlng = center,
		pic_detail = [],
		address_detail = '{pigcms{$store["adress"]}',
		attachurl = "{pigcms{$site_URl}",
		upLoadImg_url = "{pigcms{:U('Index/img_uplode')}";
    $("input[name='pic_detail']").val("{pigcms{$store['pic_info']}");
	    var  open_1 ="{pigcms{$store['open_1']}";
        var  close_1 ="{pigcms{$store['close_1']}";
        var  open_2 ="{pigcms{$store['open_2']}";
        var  close_2 ="{pigcms{$store['close_2']}";
        var  open_3 ="{pigcms{$store['open_3']}";
        var  close_3 ="{pigcms{$store['close_3']}";

        var  open_4 ="{pigcms{$store['open_4']}";
        var  close_4 ="{pigcms{$store['close_4']}";
        var  open_5 ="{pigcms{$store['open_5']}";
        var  close_5 ="{pigcms{$store['close_5']}";
        var  open_6 ="{pigcms{$store['open_6']}";
        var  close_6 ="{pigcms{$store['close_6']}";

        var  open_7 ="{pigcms{$store['open_7']}";
        var  close_7 ="{pigcms{$store['close_7']}";
        var  open_8 ="{pigcms{$store['open_8']}";
        var  close_8 ="{pigcms{$store['close_8']}";
        var  open_9 ="{pigcms{$store['open_9']}";
        var  close_9 ="{pigcms{$store['close_9']}";

        var  open_10 ="{pigcms{$store['open_10']}";
        var  close_10 ="{pigcms{$store['close_10']}";
        var  open_11 ="{pigcms{$store['open_11']}";
        var  close_11 ="{pigcms{$store['close_11']}";
        var  open_12 ="{pigcms{$store['open_12']}";
        var  close_12 ="{pigcms{$store['close_12']}";

        var  open_13 ="{pigcms{$store['open_13']}";
        var  close_13 ="{pigcms{$store['close_13']}";
        var  open_14 ="{pigcms{$store['open_14']}";
        var  close_14 ="{pigcms{$store['close_14']}";
        var  open_15 ="{pigcms{$store['open_15']}";
        var  close_15 ="{pigcms{$store['close_15']}";

        var  open_16 ="{pigcms{$store['open_16']}";
        var  close_16 ="{pigcms{$store['close_16']}";
        var  open_17 ="{pigcms{$store['open_17']}";
        var  close_17 ="{pigcms{$store['close_17']}";
        var  open_18 ="{pigcms{$store['open_18']}";
        var  close_18 ="{pigcms{$store['close_18']}";

        var  open_19 ="{pigcms{$store['open_19']}";
        var  close_19 ="{pigcms{$store['close_19']}";
        var  open_20 ="{pigcms{$store['open_20']}";
        var  close_20 ="{pigcms{$store['close_20']}";
        var  open_21 ="{pigcms{$store['open_21']}";
        var  close_21 ="{pigcms{$store['close_21']}";
	function select_time(timestr, domid) {
		var h = 0, m = 0;
		var optionstr = '';
		for (var i = 0; i < 48; i++) {
			var M;
			if (m == 0) {
				M = '00';
			} else {
				M = m;
			}
			var hsr = h < 10 ? "0" + h : h;
			var time = hsr + ' : ' + M;
			var vtime = hsr + ':' + M;
			if (timestr == vtime) {
			   optionstr = "<option value='" + vtime + "' selected='selected'>" + time + "</option>";
			} else {
			   optionstr = "<option value='" + vtime + "'>" + time + "</option>";
			}
			$(optionstr).appendTo("select[name='"+domid+"']");
			m += 30;
			if (m == 60) {
				m = 0;
				h++;
			}
		}
	}
	select_time(open_1, 'open_1');
	select_time(close_1, 'close_1');
	select_time(open_2, 'open_2');
	select_time(close_2, 'close_2');
	select_time(open_3, 'open_3');
	select_time(close_3, 'close_3');
	//@wangchuanyuan 20171102
    //周二
    select_time(open_4, 'open_4');
    select_time(close_4, 'close_4');
    select_time(open_5, 'open_5');
    select_time(close_5, 'close_5');
    select_time(open_6, 'open_6');
    select_time(close_6, 'close_6');
    //周三
    select_time(open_7, 'open_7');
    select_time(close_7, 'close_7');
    select_time(open_8, 'open_8');
    select_time(close_8, 'close_8');
    select_time(open_9, 'open_9');
    select_time(close_9, 'close_9');
    //周四
    select_time(open_10, 'open_10');
    select_time(close_10, 'close_10');
    select_time(open_11, 'open_11');
    select_time(close_11, 'close_11');
    select_time(open_12, 'open_12');
    select_time(close_12, 'close_12');
    //周五
    select_time(open_13, 'open_13');
    select_time(close_13, 'close_13');
    select_time(open_14, 'open_14');
    select_time(close_14, 'close_14');
    select_time(open_15, 'open_15');
    select_time(close_15, 'close_15');
    //周六
    select_time(open_16, 'open_16');
    select_time(close_16, 'close_16');
    select_time(open_17, 'open_17');
    select_time(close_17, 'close_17');
    select_time(open_18, 'open_18');
    select_time(close_18, 'close_18');
    //周日
    select_time(open_19, 'open_19');
    select_time(close_19, 'close_19');
    select_time(open_20, 'open_20');
    select_time(close_20, 'close_20');
    select_time(open_21, 'open_21');
    select_time(close_21, 'close_21');




	var static_public="{pigcms{$static_public}",choose_province="/merchant.php?g=Merchant&c=Area&a=ajax_province",choose_city="/merchant.php?g=Merchant&c=Area&a=ajax_city",choose_area="/merchant.php?g=Merchant&c=Area&a=ajax_area",choose_circle="merchant.php?g=Merchant&c=Area&a=ajax_circle";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>
<script src="{pigcms{$static_path}js/shop_info_img.js?ver=<php>echo time();</php>"></script>
</body>
<include file="Public:footer"/>
</html>