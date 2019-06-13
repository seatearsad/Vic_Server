<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{:L('_C_COMPLETED')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
</head>
<body>
    <section class="MyEx">
        <div class="MyEx_top">
				<if condition="$deliver_session['store_id']">
                <span class="bjt" style="background: url({pigcms{$store['image']}) center no-repeat; background-size: contain;"></span>  
                <else />
                <span class="bjt" style="background: url(<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>) center no-repeat; background-size: contain;"></span>  
                </if>
            <h2>{pigcms{$deliver_session['name']}</h2>
            <span class="sj"><if condition="$deliver_session['store_id']">{pigcms{:L('_COURIER_TXT_')}<else />{pigcms{:L('_COURIER_TXT_')}</if></span>
            <if condition="$deliver_session['store_id']">
            <span class="mc"> {pigcms{$store['name']}</span>
            </if>

            <div style="text-align: right;padding-right: 10px;font-size: 16px">
                <a href="{pigcms{:U('Deliver/tongji')}" style="color: white">{pigcms{:L('_STATISTICS_TXT_')}</a>
            </div>
        </div>
        <div class="MyEx_end">
            <ul>
                <li class="cfe">
                    <h2>{pigcms{$finish_total}</h2>
                    <p>{pigcms{:L('_C_TOTAL_ORDER_')}</p>
                </li>
                <li class="c65">
                    <h2>{pigcms{$total}</h2>
                    <p>{pigcms{:L('_C_TOTAL_INIT_')}</p>
                </li>
                <li class="c66">
                    <h2>{pigcms{$distance}</h2>
                    <p>{pigcms{:L('_C_TOTAL_DIST_')}</p>
                </li>
            </ul> 
           
        </div>
        <a href="javascript:void(0);" class="Setup"></a>
    </section>
    <section class="bottom">
        <div class="bottom_n">
            <ul>
                <li class="Statistics fl">
                    <a href="{pigcms{:U('Deliver/schedule')}">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
                </li>
                <li class="home fl">
                      <a href="{pigcms{:U('Deliver/index')}">
                        <i></i>{pigcms{:L('_HOME_TXT_')}
                      </a>
                </li>
                 <li class="My Myon fl">
                    <a href="{pigcms{:U('Deliver/info')}">{pigcms{:L('_PROFILE_TXT_')}</a>
                </li>
            </ul>
        </div>
    </section>
<script>
$(document).ready(function(){
	$('.Setup').click(function(){
		layer.open({
			title:['Reminder','background-color:#ffa52d;color:#fff;'],
			content:'Are you sure about logging out?',
			btn: ['Yes', 'No'],
			shadeClose: false,
			yes: function(){
				window.parent.location = "{pigcms{:U('Deliver/logout')}";
			}
		});
	});
});
var ua = navigator.userAgent;
if(!ua.match(/TuttiDeliver/i)) {
    navigator.geolocation.getCurrentPosition(function (position) {
        updatePosition(position.coords.latitude,position.coords.longitude);
    });
}
//ios app 更新位置
function updatePosition(lat,lng){
    var message = '';
    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
        if(result){
            message = result.message;
        }else {
            message = 'Error';
        }
    });

    return message;
}
</script>
</body>
</html>
