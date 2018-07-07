<include file="Public:header" />
    <!-- 内容头部 -->
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                    <ul>

                    </ul>
            </div>
            <div class="page-content">
                    <div class="page-content-area">
                            <div class="row">
                                    <div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
                                        <div class="widget-box">
                                            <div class="widget-header">
                                                    <h5 ><font id="note" color="red">用户统计是按地区统计的，用户必须要有完整的配送地址信息</font></h5>
                                                    <div class="year"></div>
                                                    <div class="month"></div>
                                            </div>
											
											<!--时间筛选-->
											<div id="period">
											<form id="myform" method="post" action="{pigcms{:U('Analysis/userc')}" >
												<input type="hidden" name="funcName" id="funcName" value="">
												<input type="hidden" name="type" id="type" value="">
												<input type="hidden" name="areaid" id="areaid" value="">
												<font color="#000">时间段：</font>
												<input type="text" class="input-text" name="begin_time" style="width:120px;" id="begin_time"  value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
											   
												<input type="text" class="input-text" name="end_time" style="width:120px;" id="end_time" value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
												<input type="button"  value="提交" class="button" onclick="formsend()">
											</form>
											</div>
											
                                            <div class="widget-body" id="main" style="text-align:center;">
                                                    <div style="float:left" id="user_chart">
                                                    </div>
                                                    <div style="float:left" id="rank">
                                                    </div>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <style type="text/css">
                                            table.gridtable {
                                                    font-family: verdana,arial,sans-serif;
                                                    font-size:11px;
                                                    color:#333333;
                                                    border-width: 1px;
                                                    border-color: #666666;
                                                    border-collapse: collapse;
                                            }
                                            table.gridtable th {
                                                    border-width: 1px;
                                                    padding: 8px;
                                                    border-style: solid;
                                                    border-color: #666666;
                                                    background-color: #dedede;
                                            }
                                            table.gridtable td {
                                                    border-width: 1px;
                                                    padding: 8px;
                                                    border-style: solid;
                                                    border-color: #666666;
                                                    background-color: #ffffff;
                                            }
                                            </style>
                                            <script type="text/javascript" src="{pigcms{$static_public}fushionCharts/FusionCharts.js"></script>
                                            <script type="text/javascript">
                                                var title = '';
                                                var star_year={pigcms{$star_year};
                                                var now = new Date();
                                                var now_year=now.getFullYear();
                                                var prefix = '';
                                                $(document).ready(function(){
                                                    $.ajax({
                                                        url:'/admin.php?g=System&c=Analysis&a=getmenu',
                                                        type:"post",
                                                        dataType:"JSON",
                                                        success:function(data){
                                                            $.each(data,function(func,value){
                                                                if(func=='getuserc'){
                                                                    $('.mainnav_title ul').append('<a href="JavaScript:void(0)" id ="'+func+'" onclick="ajaxsend(\''+func+'\''+",'','',2016,''"+')" class="on">'+value+'</a>');
                                                                }else{
                                                                    $('.mainnav_title ul').append('<a href="JavaScript:void(0)" id ="'+func+'" onclick="ajaxsend(\''+func+'\''+",'','',2016,''"+')">'+value+'</a>');
                                                                }
                                                            });
                                                        }
                                                    });
                                                    ajaxsend('getuserc','','',now_year,'');
                                                });
                                                function getyear(func,areaid,_type,year,month){
                                                    if(func!='getuserc'&&func!='merc'&&func!='fanc'){
                                                        $('.year').empty();
                                                        var year_list='<div id="nav" class="mainnav_title"><ul>';
                                                        year_list+='<font color="#000">年 :</font>' ;
                                                        for(var year=star_year;year<=now.getFullYear();year++){
                                                            year_list+='<a href="JavaScript:void(0)" id="'+func+year+'" onclick="ajaxsend('+"'"+func+"','"+areaid+"','"+_type+"','"+year+"',''"+')" >'+year+'</a> | ';
                                                        }
                                                        year_list+='</ul></div>'
                                                        prefix = '$';
                                                        $('.year').append(year_list);
                                                    }else{
                                                        prefix = '';
                                                        $('.year').empty();
                                                    }
                                                }
                                                function getmonth(func,areaid,_type,year,month){
                                                    if(func!='getuserc'&&func!='merc'&&func!='fanc'){
                                                        $('.month').empty();
                                                        var now = new Date();
                                                        var month_list = '<div id="nav" class="mainnav_title"><ul>';
                                                        month_list+='<font color="#000">月 :</font>' ;
                                                        var month_end = year<now.getFullYear()?12:now.getMonth()+1;
                                                        for (var i = 1; i <= month_end; i++) {
                                                           month_list +='<a href="JavaScript:void(0)" id='+func+i+' onclick="ajaxsend('+"'"+func+"','"+areaid+"','"+_type+"','"+year+"','"+i + '\')" >'+i+'月 '+'</a>';
                                                        }
                                                        month_list+='</ul></div>';
                                                        $('.month').empty();
                                                        $('.month').append(month_list);
                                                    }else{
                                                        $('.month').empty();
                                                    }
                                                }
												function formsend(){
													var form_data=$('#myform').serializeArray();
													var send = new Array();
													$.each(form_data, function(i, field){
															send[field.name]=field.value;
													});
													if(!send['begin_time']==''&&!send['end_time']==''){
														if(send['begin_time']>send['end_time']){
															window.top.msg(0,"结束时间应大于开始时间",true);
														}else{
															
															ajaxsend(send.funcName,send.areaid,send.type,'','',send.begin_time+'~'+send.end_time);
														}
													}else{
														window.top.msg(0,"时间段不能为空",true);
													}
													
												}
												
                                                function ajaxsend(func,areaid,_type,year,month,period){
													var title='';
													if(func!='getuserc'&&func!='merc'&&func!='fanc'){
														$('#period').css('display', 'block');
														$('#period').css('visibility', 'visible');
														
														if(year!=null){
															title=year+'年';
														}
														if(month.length>0){
															title+=month+'月';
														}
													
														if(period!=null&&period!='undefined'){
															periods=period.split('~');
															if(periods[0]==periods[1]){
																title=periods[0];
															}else{
																title=periods[0]+"至"+periods[1];
															}
														}
														$('#funcName').val(func);
														$('#type').val(_type);
														$('#areaid').val(areaid);
													}else{
														$('#period').css('display', 'none');
													}
													
                                                    getyear(func,areaid,_type,year,month);
                                                    year=year.length==0?now_year:year;
                                                    getmonth(func,areaid,_type,year,month);
                                                    $('.mainnav_title ul a').removeClass('on');
                                                    $('#'+func).addClass('on');
                                                    if(year!=null){
                                                        $('.year').removeClass('on');
                                                        $('#'+func+year).addClass('on');
                                                    }
                                                    if(month!=null){
                                                        $('.month').removeClass('on');
                                                        $('#'+func+month).addClass('on');
                                                    }
                                                    $.ajax({
                                                        url:'/admin.php?g=System&c=Analysis&a='+func,
                                                        type:"post",
                                                        dataType:"JSON",
                                                        data: {area_id: areaid,type:_type,year:year,month:month,period:period},
                                                        beforeSend: function(){
                                                            $('#user_chart').empty();
                                                            $('#user_chart').append('<img src="/static/kindeditor/themes/common/loading.gif"/>');
                                                        },
                                                        success:function(date){
                                                            var chartXml='';
                                                            var json=eval(date);
                                                            if(json.error.length>0){
                                                                alert(json.error);
                                                            }
															$('#note').html(date.note);
                                                            title+= json.area_pname;
                                                            if (json.msg!=null&&json.msg!='') {
                                                                $.each(json.msg,function(i,value){
                                                                    var k_=new Array();
                                                                    var link = '';
                                                                    for(var k in value){
                                                                        k_.push(k);
                                                                    }
                                                                    if(value[k_[3]]<3){
                                                                        var link='link="JavaScript:ajaxsend(\''+func+'\','+value[k_[0]]+','+value[k_[3]]+','+year+',\''+month+'\',\''+period+'\')"';
                                                                    }else if(func=='villagec'){
                                                                        var link='link="JavaScript:ajaxsend(\''+func+'\','+value[k_[0]]+','+value[k_[3]]+','+year+',\''+month+'\',\''+period+'\')"';
                                                                    }else{
                                                                        var link='link="JavaScript:tips()"';
                                                                    }
                                                                    chartXml+='<set label="'+value[k_[1]]+'" value="'+value[k_[2]]+'" '+link+' />';
                                                                });
                                                                charting(chartXml,title,'user_chart',prefix);
                                                            }else{
                                                                $('#user_chart').empty();
                                                            }
                                                            if (json.type_money!=null) {
                                                                var chartXml2='';
                                                                $.each(json.type_money,function(type,money){
                                                                    var t_=new Array();
                                                                    var link = '';
                                                                    for(var t in money){
                                                                        t_.push(t);
                                                                    }
                                                                        chartXml2+='<set label="'+money[t_[0]]+'" value="'+money[t_[2]]+'"  />';
                                                                });
                                                                $('#main').children('#chart2').remove();
                                                                $('#main').append('<div id="chart2" style="float:left"></div>');
                                                                charting(chartXml2,json.area_pname2+"缴费种类统计","chart2",prefix);
                                                            }else{
                                                                $('#main').children('#chart2').remove();
                                                            }
                                                            if (json.rank!=null&&json.rank!='') {
                                                                
                                                                var html='<table class="gridtable">';
                                                                html+='<h3>消费排名前十</h3>';
                                                                $.each(json.rank,function(key,value){
                                                                    var k_=new Array();
                                                                    html+="<tr>";
                                                                    for(var k_ in value){
                                                                        if(!isNaN(value[k_])){
                                                                            html+="<td>"+value[k_]+"元</td>";
                                                                        }else{
                                                                            html+="<td>"+value[k_]+"</td>";
                                                                        }
                                                                    }
                                                                    html+="</tr>";
                                                                });
                                                                html+='</table>';
                                                                $('#rank').empty();
                                                                $('#rank').append(html);
                                                            }else{
                                                                $('#rank').empty();
                                                            }
                                                            
                                                            if(json.msg.length==0&&func!='villagec'){
																$('#user_chart').empty();
																$('#chart2').empty();
                                                                $('#user_chart').append("<div><font size='8px' color='red'>没有查询到数据</font></div>");
                                                            }
															
															if(json.msg.length==0&&func=='villagec'&&json.type_money.length==0){
																$('#user_chart').empty();
																$('#chart2').empty();
                                                                $('#user_chart').append("<div><font size='8px' color='red'>没有查询到数据</font></div>");
                                                            }
                                                        }
                                                    });
                                                }
                                                function tips(){
                                                    window.top.msg(0,"向下没有数据了",true);
                                                }
                                                function charting(chartXml,title,at_where,prefix){
                                                    var chart_sex = new FusionCharts("{pigcms{$static_public}fushionCharts/Pie3D.swf", "ChartId", "600", "400", "0", "1");
                                                            chart_sex.setDataXML('<chart borderThickness="0" numberPrefix="'+prefix+'" formatNumberScale="0" caption="'+title+'" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">'
                                                            +chartXml+'</chart>');
                                                            chart_sex.render(at_where);
                                                }
                                                    
                                            </script>

                                        </div>
                                    </div>
                                
                            </div>
                    </div>
            </div>
<include file="Public:footer"/>

