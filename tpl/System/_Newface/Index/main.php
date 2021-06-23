<include file="Public:header" />

<script src="{pigcms{$static_path}/js/echarts.min.js"></script>

	<script type="text/javascript">
		parentShowIndex = true;
	</script>

	<div class="mainbox">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/main.css" />
		<div id="nav" class="mainnav_title">
			<a href="{pigcms{:U('Index/main')}" class="on">{pigcms{:L('_BACK_OVERVIEW_')}</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span>{pigcms{:L('_BACK_TIME_SORT_')}：</span>
			<div style="display:inline-block;">
				<select class='custom-date' id="time_value" name='select'>
				  <option  value='1'>{pigcms{:L('_BACK_TODAY_')}</option>
				  <option selected='selected' value='7'>7 {pigcms{:L('_BACK_DAYS_')}</option>
				  <option value='30'>30 {pigcms{:L('_BACK_DAYS_')}</option>
				  <option value='180'>180 {pigcms{:L('_BACK_DAYS_')}</option>
				  <option value='365'>365 {pigcms{:L('_BACK_DAYS_')}</option>
				  <option value='custom'>{pigcms{:L('_BACK_CUSTOMIZE_')}</option>
				</select>
			</div>
			<input type="button" value="{pigcms{:L('_BACK_SORT_TABLE_')}" class="button" id="time"/>
		</div>
		<div class="topTable" id="topTable">
			<div class="echart" id="echart"></div>
			<div class="chartFooter" id="chartFooter">
				<ul>
					<li class="all active" data-type="all">{pigcms{:L('_BACK_ALL_')}</li>
					<li class="group" data-type="group">{pigcms{:L('_BACK_LUNCH_')}</li>
					<li class="shop" data-type="shop">{pigcms{:L('_BACK_DELIVERY_')}</li>
					<li class="meal" data-type="meal">{pigcms{:L('_BACK_DINE_')}</li>
					<if condition="$config['appoint_page_row']">
						<li class="appoint" data-type="appoint">{pigcms{:L('_BACK_RESE_')}</li>
					</if>
					<!--if condition="$config['is_cashier'] OR $config['pay_in_store']">
						<li class="store" data-type="store">到店</li>
					</if-->
					<if condition="$config['is_open_weidian']">
						<li class="weidian" data-type="weidian">微店</li>
					</if>
					<!--if condition="$config['wxapp_url']">
						<li class="wxapp" data-type="wxapp">营销</li>
					</if-->
					<!--li>营销</li-->
				</ul>
			</div>
			<div class="chartWidget" id="chartWidget">
				<div class="chartData">
					<div class="chartDataCon" id="chartDataCon">
						<p style="line-height:0.5em">{pigcms{:L('_BACK_TOTAL_ORDER_')}：<span id="orderCountNum"></span></p>
						<p style="line-height:0.5em">{pigcms{:L('_BACK_TOTAL_CONS_')}：<span id="consumeCountNum"></span></p>
						<p style="line-height:1em">{pigcms{:L('_BACK_TOTAL_WEIXIN_')}：$<span id="weixinPaymoney"></span></p>
						<p style="line-height:0.5em">{pigcms{:L('_BACK_TOTAL_ALIPAY_')}：$<span id="alipayPaymoney"></span></p>
					</div>
				</div>
				<div class="chartDataDown">
					<input type="button" class="chartDataDownBtn" id="chartDataDownBtn" onclick="exports();" value="{pigcms{:L('_BACK_DOWNLOAD_TABLE_')}"/>
				</div>
			</div>
		</div>
		<div class="bottomTable" id="bottomTable" style="margin-top:5px;">
			<div class="box" style="width:35%;" id="bottomTableLeft">
				<div class="top">{pigcms{:L('_BACK_DATA_OVER_')}</div>
				<div class="body">
					<div>
						<ul>
							<li><b>{pigcms{:L('_BACK_TOTAL_INCOME_')}</b><br><span>${pigcms{$website_collect_count}</span></li>
							<li><b>{pigcms{:L('_BACK_TOTAL_USER_')}</b><br><span>{pigcms{$website_user_count}</span></li>
							<li><b>{pigcms{:L('_BACK_TOTAL_MER_')}</b><br><span>{pigcms{$website_merchant_count}</span></li>
							<li><b>{pigcms{:L('_BACK_TOTAL_STORE_')}</b><br><span>{pigcms{$website_merchant_store_count}</span></li>
							<li><b>{pigcms{:L('_BACK_TOTAL_LUNCH_')}</b><br><span>{pigcms{$group_group_count}</span></li>
							<li><b>{pigcms{:L('_BACK_TOTAL_PICK_')}</b><br><span>{pigcms{$meal_store_count}</span></li>
							<li><b>{pigcms{:L('_BACK_TOTAL_DELIVER_')}</b><br><span>{pigcms{$shop_store_count}</span></li>
							<li><b>{pigcms{:L('_BACK_TOTAL_BOOKING_')}</b><br><span>{pigcms{$appoint_group_count}</span></li>
							<li><b style="color:#CC3366;">{pigcms{:L('_BACK_PENDING_MER')}</b><br><span style="color:#CC3366;">{pigcms{$merchant_verify_count}</span></li>
							<li><b style="color:#CC3366;">{pigcms{:L('_BACK_PENDING_STORE_')}</b><br><span style="color:#CC3366;">{pigcms{$merchant_verify_store_count}</span></li>
							<li><b style="color:#CC3366;">{pigcms{:L('_BACK_PENDING_LUNCH')}</b><br><span style="color:#CC3366;">{pigcms{$group_verify_count}</span></li>
							<li><b></b><br><span></span></li>
						</ul>
					</div>
					<div style="clear:both;"></div>
				</div>
				<!--div class="top">商家余额(商家总余额：{pigcms{$mer_money.all_mer_money} 元,商家待提现：{pigcms{$mer_money['all_need_pay']} 元)</div-->
				<div class="body">
					<div id="merchantMoneyEcharts" style="background:#3486AC; /* margin: 0 auto; */">
						
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="box" style="border-left:1px solid #f1f1f1;width:64.5%;" id="bottomTableRight">
				<div class="top">{pigcms{:L('_BACK_USER_ANA_')}</div>
				<div class="body" id="userEcharts">
					<div id="userSexEcharts" >
						
					</div>
					<div id="userWechatEcharts" >
						
					</div>
					<div id="userPhoneEcharts" >
						
					</div>
					<div id="userAppEcharts" >
						
					</div>
					<p style="clear:both;"></p>
				</div>
			</div>
			<p style="clear:both;"></p>
		</div>
	</div>
	<script type="text/javascript">
		var echart_data =null;
		$(function(){
			var all_date;
			windowResize();
			$(window).resize(function(){
				windowResize();
			});
			// $.post('{pigcms{:U('Index/ajax_all_date')}', {}, function(data, textStatus, xhr) {
				// echart_data = data;
				// show_chart();
			// });
			$('.chartFooter li').mouseover(function() {
				var type = $(this).attr('data-type');
				$('.chartFooter li').removeClass('active')
				$(this).addClass('active');
				show_chart(type);
				
			})
			
			$('#time').click(function(){
				var day='';
				var period='';
				if($('#time_value option:selected').attr('value')=='custom'){
					period = $('#time_value option:selected').html();
				}else{
					day = $('#time_value option:selected').attr('value');
				}
				
				$.ajax({
					url: '{pigcms{:U('Index/ajax_all_date')}',
					type: 'POST',
					dataType: 'json',
					data: {day: day,period:period},
					success:function(date){
						if(typeof(date.error_code)!='undefined'&&date.error_code){
							parent.msg(data.msg);
						}else{
							echart_data = date;
							//console.log(echart_data);
							show_chart();
						}
					}
				});	
			});
			$('#time').trigger('click');
			
		});
		
		function exports(){
			if($('#time_value option:selected').attr('value')=='custom'){
				period = $('#time_value option:selected').html();
				var export_url ="{pigcms{:U('Index/ajax_all_date')}&period="+period+'&type='+$('.chartFooter .active').attr('data-type');
			}else{
				day = $('#time_value option:selected').attr('value');
				var export_url ="{pigcms{:U('Index/ajax_all_date')}&day="+day+'&type='+$('.chartFooter .active').attr('data-type');
			}
			window.location.href = export_url;
		}
	   
		
		function windowResize(){
			$('#topTable').height(($(window.parent).height()-50)/2);
			$('#echart').height($('#topTable').height()-80);
			
			$('#chartFooter ul ').width($('#chartFooter').width()-220);
			$('#echart').width($('#chartFooter').width()-220);
			
			var echartSize = $('#chartFooter ul li').size();
			$('#chartFooter').addClass('size-'+echartSize);
			
			/* $('#bottomTable').css('min-height',$(window).height() - ($(window.parent).height()-50)/2); */
			
			
			// var merchantMoneyEchartsWidth = ($('#bottomTableLeft').width()-20)*0.8;
			// if(merchantMoneyEchartsWidth > 360){
			// 	merchantMoneyEchartsWidth = 360;
			// }
			// $('#merchantMoneyEcharts').height(merchantMoneyEchartsWidth);
			// $('#merchantMoneyEcharts').width(merchantMoneyEchartsWidth);
			
			
			var merchantUserEchartsWidth = ($('#bottomTableRight').width())*0.4;
			if(merchantUserEchartsWidth > 360){
				merchantUserEchartsWidth = 360;
			}
			$('#userEcharts div').height(merchantUserEchartsWidth);
			$('#userEcharts div').width(merchantUserEchartsWidth);
			$('#userEcharts div:odd').css('margin-right','0px');
			
			if(echart_data!=null){
				show_chart($('.chartFooter .active').attr('data-type'));
			}
			//数据分析
			var charts = Object();
			//var pay_date = echart_data['pay_type'];
	
			charts = {
				weixin:{name:'userWechatEcharts',title:"{pigcms{:L('_BACK_WECHAT_ANA_')}",part1:{value:'{pigcms{$user.weixin}',name:"{pigcms{:L('_BACK_AUTH_')}",color:'#27c24c'},part2:{value:'{pigcms{$user['user_count']-$user['weixin']}',name:"{pigcms{:L('_BACK_UNAUTH_')}",color:'#CCC'}},
				phone:{name:'userPhoneEcharts',title:"{pigcms{:L('_BACK_PHONE_ANA_')}",part1:{value:'{pigcms{$user.phone}',name:"{pigcms{:L('_BACK_CONNECTED_')}",color:'#3CB9B3'},part2:{value:'{pigcms{$user['user_count']-$user['phone']}',name:"{pigcms{:L('_BACK_NOT_CONN_')}",color:'#CCC'}},
				//paytype:{name:'userPaytypeEcharts',title:'微信支付宝支付数据分析',part1:{value:pay_date.weixin,name:'微信支付',color:'#27c24c'},part2:{value:pay_date.alipay,name:'支付宝支付',color:'#CCC'}},
				<if condition="C('config.pay_weixinapp_open')">app:{name:'userAppEcharts',title:'APP用户分析',part1:{value:'{pigcms{$user.app}',name:'使用',color:'#E37979'},part2:{value:'{pigcms{$user['user_count']-$user['phone']}',name:'未使用',color:'#D9D154'}},</if>
			}
			var sex = Object();
			sex = {name:'userSexEcharts',title:"{pigcms{:L('_BACK_GENDER_ANA_')}",part1:{value:'{pigcms{$user.men}',name:"{pigcms{:L('_BACK_MALE_')}",color:'#00A79D'},part2:{value:'{pigcms{$user.women}',name:"{pigcms{:L('_BACK_FEMALE_')}",color:'#ED0B5F'},part3:{value:'{pigcms{$user.unknow_user}',name:"{pigcms{:L('_BACK_UNDEFINED_')}",color:'#FC9F1E'}},
				
			$.each(charts, function(index, val) {
				var index  = echarts.init(document.getElementById(val.name));
				option = {
					 title: {
						text: val.title,
						left: 'center',
						top: 20,
						textStyle: {
							color: '#000'
						}
					},
					 tooltip : {
						trigger: 'item',
						formatter: "{a} <br/>{b} : {c} ({d}%)"
					},
					// legend: {
						// orient: 'vertical',
						// left: 'left',
						// data: [val.part1.name,val.part2.name]
					// },
					series : [
						{
							name: '',
							type: 'pie',
							radius : '55%',
							avoidLabelOverlap: false,
							selectedMode: 'multiple',
							center: ['50%', '50%'],
							 label: {
								normal: {
									position: 'inner'
								}
							},
							labelLine: {
								normal: {
									show: false
								}
							},
							data:[
								{value:val.part1.value, name:val.part1.name,itemStyle:{normal:{color:val.part1.color}},selected:true},
								{value:val.part2.value, name:val.part2.name,itemStyle:{normal:{color:val.part2.color}}},
								// {value:val.part3.value, name:val.part3.name,itemStyle:{normal:{color:val.part3.color}}},
								// {value:val.part4.value, name:val.part4.name,itemStyle:{normal:{color:val.part4.color}}},
							],
							itemStyle: {
								emphasis: {
									shadowBlur: 10,
									shadowOffsetX: 0,
									shadowColor: 'rgba(0, 0, 0, 0.5)'
								}
							}
						}
					]
				};
				
			  index.setOption(option);
			});
			
			var sexs  = echarts.init(document.getElementById(sex.name));
				option_sex = {
					 title: {
						text: sex.title,
						left: 'center',
						top: 20,
						textStyle: {
							color: '#000'
						}
					},
					 tooltip : {
						trigger: 'item',
						formatter: "{a} <br/>{b} : {c} ({d}%)"
					},
					// legend: {
						// orient: 'vertical',
						// left: 'left',
						// data: [val.part1.name,val.part2.name]
					// },
					series : [
						{
							name: '',
							type: 'pie',
							radius : '55%',
							avoidLabelOverlap: false,
							selectedMode: 'multiple',
							center: ['50%', '50%'],
							 label: {
								normal: {
									position: 'inner'
								}
							},
							labelLine: {
								normal: {
									show: false
								}
							},
							data:[
								{value:sex.part1.value, name:sex.part1.name,itemStyle:{normal:{color:sex.part1.color}}},
								{value:sex.part2.value, name:sex.part2.name,itemStyle:{normal:{color:sex.part2.color}},selected:true},
								{value:sex.part3.value, name:sex.part3.name,itemStyle:{normal:{color:sex.part3.color}}},
							],
							itemStyle: {
								emphasis: {
									shadowBlur: 10,
									shadowOffsetX: 0,
									shadowColor: 'rgba(0, 0, 0, 0.5)'
								}
							}
						}
					]
				};
				
			  sexs.setOption(option_sex);
			
			//var mer = Object();
			// mer = {name:'merchantMoneyEcharts',title:'待提现金额占比',
			// 	part1:{
			// 		value:'{pigcms{$mer_money.all_money}',name:'商家总余额',color:'red'
			// 	},
			// 	// part2:{
			// 		// value:'{pigcms{$mer_money.all_mer_money}',name:'商家总余额',color:'blue'
			// 	// },
			// 	part3:{
			// 		value:'{pigcms{$mer_money['all_need_pay']/100}',name:'待提现金额',color:'green'
			// 	},
			// 	// part4:{
			// 		// value:'{pigcms{$mer_money.all_count}',name:'女性用户',color:'yellow'
			// 	// },
			// };
			
			// var mer_money  = echarts.init(document.getElementById(mer.name));
			// 	option_mer = {
			// 		title: {
			// 			text: mer.title,
			// 			x: 'center',
			// 			y: 'center',
			// 			itemGap: 20,
			// 			textStyle : {
			// 				color : 'red',
			// 				fontFamily : '微软雅黑',
			// 				fontSize : '14',
			// 				fontWeight : 'bold'
			// 			}
			// 		},
			//
			// 		tooltip: {},
			// 		series : [
			// 			{
			// 				name:'',
			// 				type:'pie',
			// 				radius: ['50%', '70%'],
			// 				avoidLabelOverlap: false,
			// 				center: ['50%', '50%'],
			// 				 label: {
			// 					normal: {
			// 						textStyle: {
			// 							fontSize: 12,
			// 							color: '#235894'
			// 						}
			// 					}
			// 				},
			//
			// 				 labelLine: {
			// 					normal: {
			// 						lineStyle: {
			// 							color: '#235894'
			// 						}
			// 					}
			// 				},
			// 				data:[
			// 					{value:mer.part1.value, name:mer.part1.name},
			// 					{
			// 						value:mer.part3.value,
			// 						name:mer.part3.name,
			// 						selected:true
			// 					},
			//
			// 				],
			// 			}
			// 		]
			// 	};
			//
			//   mer_money.setOption(option_mer);
		}

		
		function show_chart(type){
			if(!type){
				type='all';
			}
			var data = echart_data;
			
			$('#orderCountNum').html(data.count.sales_count[type]);
			$('#consumeCountNum').html(data.count.consume[type]);
			$('#weixinPaymoney').html(data.pay_type.weixin);
			$('#alipayPaymoney').html(data.pay_type.alipay)
			
			
			var myCharts  = echarts.init(document.getElementById('echart'));
			
			var ob = new Array();
			var income = new Array();
			var mer_income = new Array();
			//console.log(data);
			var chart_title = data.alias_name[type];
			$.each(data.xAxis_txt,function(i,v){
				ob.push(v);
				income.push(data[type].income_txt[i]);
				mer_income.push(data[type].mer_income_txt[i]);
			});
		
			var myDate = new Date();
			var mytime=myDate.toLocaleString();  
			var subtxt = $('#time_value option:selected').html()+'  '+mytime;
			
			var option = {
				
				title : {
					//text: chart_title+'数据分析',
                    text:"{pigcms{:L('_BACK_SALES_ANA_')}",
					x:'left',
					textStyle:{
						color:'#fff'
					},
					padding:[
						20,10,10,40
					],
					
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data:["{pigcms{:L('_BACK_TOTAL_SALES_')}","{pigcms{:L('_BACK_ACT_SALES_')}"],
					textStyle:{
						color:'#fff'
					},
					padding:[
						20,10,10,40
					]
				},
				grid: {
					left: '40',
					right: '50',
					top: '70',
					bottom: '15',
					containLabel: true
				},
				toolbox: {
					// backgroundColor: '#fff', // 工具箱背景颜色
					padding: [5,50,5,10],
					itemGap:15,
					feature : {
						magicType: {
							show : true,
							title : {
								line : "{pigcms{:L('_BACK_LINE_CHART_')}",
								bar : "{pigcms{:L('_BACK_BAR_CHART_')}",
								
							},
							type : ['line', 'bar'],
							icon : {
								'line':'image://{pigcms{$static_path}images/echarts_quxian.png',
								'bar':'image://{pigcms{$static_path}images/echarts_zhuzhuang.png',
							},
							iconStyle:{
								emphasis:{
									color:'white'
								}
							}
						},
						restore : {
							show : true,
							title : "{pigcms{:L('_BACK_INIT_TABLE_')}",
							icon:'image://{pigcms{$static_path}images/echarts_refresh.png',
							iconStyle:{
								emphasis:{
									color:'white'
								}
							}
						},
						saveAsImage : {
							show: true,
							title : "{pigcms{:L('_BACK_SAVE_IMG_')}",
							name : chart_title+'数据分析('+subtxt+')',
							// icon : chart_title+'数据分析('+subtxt+')',
							lang : ['点击保存'],
							icon:'image://{pigcms{$static_path}images/echarts_down.png',
							backgroundColor:'#029BDC',
							pixelRatio:2,
							iconStyle:{
								emphasis:{
									color:'white'
								}
							}
						}
					},
					top:15
				},
				calculable : true,
				xAxis : 
					{
					
						type : 'category',
						boundaryGap : false,
						data :  ob,
						splitLine: {
							show: false
						},
						axisLine: {
							lineStyle: {
								color: '#fff' //坐标轴线颜色
							}
						},
					}
				,
				yAxis : [
					{
						type : 'value',
						splitLine: {
							show: false
						},
						axisLine: {
							lineStyle: {
								color: '#fff' //坐标轴线颜色
							}
						},	
					}
				],
				
				series : [
					{
						name:"{pigcms{:L('_BACK_TOTAL_SALES_')}",
						type:'line',
						tiled: '总量',
						smooth:true,
						itemStyle : {
							normal : {
								color:'#C3E870',
								label : {
									show : true,
									formatter : '{c}',
									position : 'top'
								},
								lineStyle:{
									color:'#9CD028'
								},
								itemStyle: {
									normal: {
										color: '#fff'
									}
								},
								areaStyle: {type: 'default'}
							}
						},
						data: income
					},
					{
						name:"{pigcms{:L('_BACK_ACT_SALES_')}",
						type:'line',
						tiled: '总量',
						smooth:true,
						itemStyle : {
							normal : {
								color:'#4BC490',
								label : {
									show : true,
									formatter : '{c}',
									position : 'top'
								},
								lineStyle:{
									color:'#4BC495'
								},
								areaStyle: {type: 'default'}
							}
						},
						data: mer_income
					},

				]
				
				 

			};
			
			myCharts.setOption(option);
		}
		
	</script>
	<script type="text/javascript" src="{pigcms{$static_public}js/date-picker/index.js"></script>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/date-picker/index.css" />
<include file="Public:footer"/>