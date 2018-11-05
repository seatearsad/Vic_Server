<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$deliver_session['name']}-{pigcms{:L('_STATISTICS_TXT_')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>

<body>
    <section class="Statistics">
        <div class="Statistics_top clr">
            <a href="javascript:void(0);" id="begin">
                <h2><i>{pigcms{:L('_START_TIME_')}</i></h2>
                <input type="text" readonly="readonly" placeholder="{pigcms{:L('_SELECT_TIME_')}"  name="appDate" id="appDate" value="{pigcms{$begin_time}">
            </a>
            <a href="javascript:void(0)" id="end">
                <h2><i>{pigcms{:L('_END_TIME_')}</i></h2>
                <input type="text" readonly="readonly" placeholder="{pigcms{:L('_SELECT_TIME_')}"  name="appDate1" id="appDate1" value="{pigcms{$end_time}">
            </a>
        </div>

        <div class="Statistics_end clr">
            <ul>
                <li>
                    <a href="javascript:void(0);">
                        <div class="cfd">
                            <img src="{pigcms{$static_path}images/tjt_10.png" width=23 height=20>
                            <p><i>$</i>{pigcms{$online_money|floatval}</p>
                        </div>
                        <h2>{pigcms{:L('_ONLINE_PAY_')}</h2>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);">
                        <div class="c3a">
                            <img src="{pigcms{$static_path}images/tjt_07.png" width=22 height=20>
                            <p><i>$</i>{pigcms{$freight_charge|floatval}</p>
                        </div>
                        <h2>{pigcms{:L('_DELI_PRICE_')}</h2>
                    </a>
                </li>
                <if condition="empty($deliver_session['store_id'])">
                <li>
                    <a href="javascript:void(0);">
                        <div class="c80">
                            <img src="{pigcms{$static_path}images/tjt_17.png" width=20 height=20>
                            <p><i></i>{pigcms{$change_count|default=0}</p>
                        </div>
                        <h2>{pigcms{:L('_SYS_TRAN_ORDER_')}</h2>
                    </a>
                </li>
                </if>
                <li>
                    <a href="javascript:void(0);">
                        <div class="cff">
                            <img src="{pigcms{$static_path}images/tjt_14.png" width=20 height=20>
                            <p><i>$</i>{pigcms{$offline_money|floatval}</p>
                        </div>
                        <h2>{pigcms{:L('_CASH_ON_DELI_')}</h2>
                    </a>
                </li>
                <if condition="empty($deliver_session['store_id'])">
                <li>
                    <a href="javascript:void(0);">
                        <div class="c0a">
                            <img src="{pigcms{$static_path}images/tjt_25.png" width=19 height=20>
                            <p><i></i>{pigcms{$system_count|default=0}</p>
                        </div>
                        <h2>{pigcms{:L('_SYS_ASS_ORDER_')}</h2>
                    </a>
                </li>
                </if>
                <li>
                    <a href="javascript:void(0);">
                        <div class="c07">
                            <img src="{pigcms{$static_path}images/tjt_22.png" width=15 height=20>
                            <p><i></i>{pigcms{$self_count|default=0}</p>
                        </div>
                        <h2>{pigcms{:L('_INIT_GRAB_ORDER_')}</h2>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    <section class="bottom">
        <div class="bottom_n">
            <ul>
                <li class="Statistics Statisticson fl">
                    <a href="javascript:void(0);">{pigcms{:L('_STATISTICS_TXT_')}</a>
                </li>
                <li class="home fl">
                      <a href="{pigcms{:U('Deliver/index')}">
                        <i></i>{pigcms{:L('_HOME_TXT_')}
                      </a>
                </li>
                 <li class="My fl">
                    <a href="{pigcms{:U('Deliver/info')}">{pigcms{:L('_PROFILE_TXT_')}</a>
                </li>
            </ul>
        </div>
    </section>
<script type="text/javascript">
$(function () {
	var begin = {};
	begin.date = {preset : 'date'};
	begin.default = {
	        theme: 'android-ics light', //皮肤样式
	        mode: 'scroller', //日期选择模式
			display: 'bottom', //显示方式
			dateFormat: 'yyyy-mm-dd',
			onSelect: function (valueText, inst) {
				$("#appDate").val(valueText);
				if ($("#appDate1").val() == '') {
				} else {
					location.href="{pigcms{:U('Deliver/tongji')}&begin_time="+valueText+'&end_time='+$("#appDate1").val();
				}
	        }
	};
	var enddate = {};
	enddate.date = {preset : 'date'};
	enddate.default = {
	        theme: 'android-ics light', //皮肤样式
	        mode: 'scroller', //日期选择模式
			display: 'bottom', //显示方式
			dateFormat: 'yyyy-mm-dd',
			onSelect: function (valueText, inst) {
				$("#appDate1").val(valueText);
				if ($("#appDate").val() == '') {
				} else {
					location.href="{pigcms{:U('Deliver/tongji')}&end_time="+valueText+'&begin_time='+$("#appDate").val();
				}
	        }
	};
	$("#begin").mobiscroll($.extend(begin['date'], begin['default']));
	$("#end").mobiscroll($.extend(enddate['date'], enddate['default']));
});
</script>
</body>
</html>