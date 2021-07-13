<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
<!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{pigcms{:L('_BACK_OVERVIEW_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
<!--                <li class="breadcrumb-item">-->
<!--                    <a>UI Elements</a>-->
<!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_OVERVIEW_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="row wrapper wrapper-content animated fadeInRight" id="main_chart">
        <div class="col-lg-9">
            <div class="ibox ">
                <div class="ibox-content">
                    <div >
                        <span class="float-right text-right">
                            <div class="btn-group" id="main_select">
                                <button class="btn btn-white " data-type="day">Today</button>
                                <button class="btn btn-white active" data-type="week">7-Day</button>
                                <button class="btn btn-white" data-type="month">30-Day</button>
                            </div>
                        </span>
                        <h1 class="m-b-xs" id="cash_total">$ 0</h1>
                        <h3 class="font-bold no-margins">
                            Total Cash Flow
                        </h3>
                        <!--small>Sales marketing.</small-->
                    </div>

                    <div>
                        <canvas id="lineChart" {pigcms{$height}></canvas>
                    </div>

                    <div class="m-t-md">
                        <!--small class="float-right">
                            <i class="fa fa-clock-o"> </i>
                            Update on 16.07.2015
                        </small>
                        <small>
                            <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over $50,000.
                        </small-->
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-primary float-right">Today</span>
                    </div>
                    <h5>Cash Flow</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins" id="today_cash">0</h1>
                    <!--div class="stat-percent font-bold text-navy">20% <i class="fa fa-level-up"></i></div-->
                    <small>Cash Flow</small>
                </div>
            </div>
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-primary float-right" id="user_title">Today</span>
                    </div>
                    <h5>Total Registration</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins" id="all_user"></h1>
                    <!--div class="stat-percent font-bold text-navy">20% <i class="fa fa-level-up"></i></div-->
                    <small>New Users</small>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="ibox ">
                <div class="ibox-content">
                    <div>
                        <span class="float-right text-right">
                            <select name="city_select" id="city_select" class="form-control">
                                <option value="0">Select a state</option>
                                <volist name="city" id="vo">
                                    <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                                </volist>
                            </select>
                        </span>
                        <h1 class="m-b-xs" id="city_total">$ 0</h1>
                        <h3 class="font-bold no-margins">
                            Total Cash Flow
                        </h3>
                        <!--small>Sales marketing.</small-->
                    </div>

                    <div>
                        <canvas id="lineCityChart" {pigcms{$height}></canvas>
                    </div>

                    <div class="m-t-md">
                        <!--small class="float-right">
                            <i class="fa fa-clock-o"> </i>
                            Update on 16.07.2015
                        </small>
                        <small>
                            <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over $50,000.
                        </small-->
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-primary float-right">Today</span>
                    </div>
                    <h5>Cash Flow by City</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins" id="city_total_label">0</h1>
                    <!--div class="stat-percent font-bold text-navy">20% <i class="fa fa-level-up"></i></div-->
                    <small>Cash Flow</small>
                </div>
            </div>
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="ibox-tools">
                        <span class="label label-primary float-right">Today</span>
                    </div>
                    <h5>New Users by City</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins" id="city_user">0</h1>
                    <!--div class="stat-percent font-bold text-navy">20% <i class="fa fa-level-up"></i></div-->
                    <small>New Users</small>
                </div>
            </div>
        </div>
    </div>
    <script src="{pigcms{$static_path}js/plugins/chartJs/Chart.min.js"></script>
    <script>

        $(document).ready(function() {
            var select_day = $('#main_select').children('button.active').data('type');
            var user_title = $('#main_select').children('button.active').html();
            var city_id = $('#city_select').val();

            getData(select_day,city_id,user_title);
        });
        var dd=null;
        var dc=null;
        var all_times = 0;
        function getData(select_day,city_id,user_title){
            var re_data = {'day':select_day,'city_id':city_id};
            $("#user_title").html(user_title);
            $.post("{pigcms{:U('Index/ajax_new_data')}",re_data,function(data){
                if(data == ""){
                    $("#main_chart").hide();
                }else {
                    if(typeof(data.total) == "undefined"){
                        all_times++;
                        if(all_times < 5) {
                            setTimeout(function () {
                                getData(select_day, city_id, user_title);
                            }, 2000);
                        }
                    }else {
                        all_times = 0;
                        $("#main_chart").show();


                    dd=data.data_array;
                    dc=data.city_array;

                    g_td=createChart(dd, 'lineChart',70);
                    g_tc=createChart(dc, 'lineCityChart',70);


                        $('#cash_total').html('$ ' + data.total);
                        $('#today_cash').html('$ ' + data.today_cash);

                        $('#city_total').html('$ ' + data.city_total);
                        $('#city_total_label').html('$ ' + data.city_total);

                        $('#city_select').val(data.city_id);

                        $('#all_user').html(data.all_user);
                        $('#city_user').html(data.city_user);
                    }
                }
            });
        }
        // Minimalize menu when screen is less than 768px
        var last_mode=0;
        $(window).bind("resize", function () {
            // if (window.innerWidth  < 769) {
            //     if (last_mode!=-1){
            //         alert("111");
            //         last_mode=-1;
            //         if (dd!=null){
            //             if (g_td!=null) {
            //                 canvas = document.getElementById("lineChart");
            //                 canvas.height = 170;
            //                 //g_td.resize();
            //                 alert("222");
            //                 console.log("------------------------");
            //             }
            //             if (g_tc!=null) {
            //
            //                 canvas = document.getElementById("lineCityChart");
            //                 canvas.height = 170;
            //                 //g_tc.resize();
            //                 console.log("------------------------");
            //             }
            //         }
            //     }
            //     //$('body').addClass('body-small')
            // } else {
            //     if (last_mode!=1){
            //         last_mode=1;
            //         if (dd!=null) {
            //             if (g_td!=null) {
            //                 canvas = document.getElementById("lineChart");
            //                 canvas.height = 70;
            //                 g_td.resize();
            //                 console.log("+++++++++++++++++++");
            //             }
            //             if (g_tc!=null) {
            //                 canvas = document.getElementById("lineCityChart");
            //                 canvas.height = 70;
            //                 g_tc.resize();
            //                 console.log("+++++++++++++++++++");
            //             }
            //         }
            //     }
            //    // $('body').removeClass('body-small')
            // }
        });
        var g_td=null;
        var g_tc=null;
        function createChart(data,id){
            var time_arr = [];
            var cash_arr = [];
            var sales_arr = [];
            for(var key in data) {
                time_arr.push(key);
                cash_arr.push(data[key]['cash_flow']);
                sales_arr.push(data[key]['sales']);
            }

            var all_data = {
                'time':time_arr,
                'cash':cash_arr,
                'sales':sales_arr
            };

            var lineData = {
                labels:all_data['time'],
                datasets: [
                    {
                        label: "Cash Flow",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: all_data['cash']
                    },
                    {
                        label: "Sales",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: all_data['sales']
                    }
                ]
            };

            var lineOptions = {
                hover: {
                    animationDuration: 0  // 防止鼠标移上去，数字闪烁
                },
                "animation": {
                    "duration": 1,
                    "onComplete": function() {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function(dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    if( dataset.data.length <= 7 || (dataset.data.length > 7 && index%2 == 1)) {
                                        var data = dataset.data[index];
                                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                    }
                                });
                        });
                    }
                },
                responsive: true
            };

            //var canvas=document.getElementById(id);
            var ctx = document.getElementById(id).getContext("2d");
            td=new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
            return td;
        }

        $('#main_select').children('button').each(function () {
            $(this).click(function () {
                var select_day = $(this).data('type');
                var city_id = $('#city_select').val();
                var user_title = $(this).html();
                getData(select_day,city_id,user_title);
                $(this).addClass('active').siblings().removeClass('active');
            });
        });

        $('#city_select').change(function () {
            var select_day = $('#main_select').children('button.active').data('type');
            var user_title = $('#main_select').children('button.active').html();
            var city_id = $(this).val();
            getData(select_day,city_id,user_title);
        });
    </script>
<!----------------------------------------    以下不要写代码     ------------------------------------------------>
<include file="Public:footer"/>