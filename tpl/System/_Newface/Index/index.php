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
    <div class="row wrapper wrapper-content animated fadeInRight">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div>
                        <span class="float-right text-right">
                        <small>Average value of sales in the past month in: <strong>United states</strong></small>
                            <br/>
                            All sales: 162,862
                        </span>
                        <h1 class="m-b-xs">$ 50,992</h1>
                        <h3 class="font-bold no-margins">
                            Half-year revenue margin
                        </h3>
                        <small>Sales marketing.</small>
                    </div>

                    <div>
                        <canvas id="lineChart" height="70"></canvas>
                    </div>

                    <div class="m-t-md">
                        <small class="float-right">
                            <i class="fa fa-clock-o"> </i>
                            Update on 16.07.2015
                        </small>
                        <small>
                            <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over $50,000.
                        </small>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>3D Buttons</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#" class="dropdown-item">Config option 1</a>
                            </li>
                            <li><a href="#" class="dropdown-item">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <p>
                        To add three diminsion to buttons You can add <code>.dim</code> class to button.
                    </p>
                    <h3 class="font-bold">Three diminsion button</h3>

                    <button class="btn btn-primary dim btn-large-dim" type="button"><i class="fa fa-money"></i>
                    </button>
                    <button class="btn btn-warning dim btn-large-dim" type="button"><i class="fa fa-warning"></i>
                    </button>
                    <button class="btn btn-danger  dim btn-large-dim" type="button"><i class="fa fa-heart"></i>
                    </button>
                    <button class="btn btn-primary  dim btn-large-dim" type="button"><i class="fa fa-dollar"></i>6
                    </button>
                    <button class="btn btn-info  dim btn-large-dim btn-outline" type="button"><i
                                class="fa fa-ruble"></i></button>
                    <button class="btn btn-primary dim" type="button"><i class="fa fa-money"></i></button>
                    <button class="btn btn-warning dim" type="button"><i class="fa fa-warning"></i></button>
                    <button class="btn btn-primary dim" type="button"><i class="fa fa-check"></i></button>
                    <button class="btn btn-success  dim" type="button"><i class="fa fa-upload"></i></button>
                    <button class="btn btn-info  dim" type="button"><i class="fa fa-paste"></i></button>
                    <button class="btn btn-warning  dim" type="button"><i class="fa fa-warning"></i></button>
                    <button class="btn btn-default  dim " type="button"><i class="fa fa-star"></i></button>
                    <button class="btn btn-danger  dim " type="button"><i class="fa fa-heart"></i></button>

                    <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-money"></i>
                    </button>
                    <button class="btn btn-outline btn-warning dim" type="button"><i class="fa fa-warning"></i>
                    </button>
                    <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-check"></i>
                    </button>
                    <button class="btn btn-outline btn-success  dim" type="button"><i class="fa fa-upload"></i>
                    </button>
                    <button class="btn btn-outline btn-info  dim" type="button"><i class="fa fa-paste"></i></button>
                    <button class="btn btn-outline btn-warning  dim" type="button"><i class="fa fa-warning"></i>
                    </button>
                    <button class="btn btn-outline btn-danger  dim " type="button"><i class="fa fa-heart"></i>
                    </button>

                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Button dropdowns</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content float-e-margins">
                            <p>
                                Droppdowns buttons are avalible with any color and any size.
                            </p>

                            <h3 class="font-bold">Dropdowns</h3>


                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#" class="font-bold">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle">Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>

                            <br/>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <br/>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-warning btn-xs dropdown-toggle">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Grouped Buttons</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <p>
                                This is a group of buttons, ideal for sytuation where many actions are related to
                                same element.
                            </p>

                            <h3 class="font-bold">Button Group</h3>
                            <div class="btn-group">
                                <button class="btn btn-white" type="button">Left</button>
                                <button class="btn btn-primary" type="button">Middle</button>
                                <button class="btn btn-white" type="button">Right</button>
                            </div>
                            <br/>
                            <br/>
                            <div class="btn-group">
                                <button type="button" class="btn btn-white"><i class="fa fa-chevron-left"></i>
                                </button>
                                <button class="btn btn-white">1</button>
                                <button class="btn btn-white  active">2</button>
                                <button class="btn btn-white">3</button>
                                <button class="btn btn-white">4</button>
                                <button type="button" class="btn btn-white"><i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Icon Buttons</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#" class="dropdown-item">Config option 1</a>
                            </li>
                            <li><a href="#" class="dropdown-item">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content float-e-margins">
                    <p>
                        To buttons with any color or any size you can add extra icon on the left or the right side.
                    </p>

                    <h3 class="font-bold">Commom Icon Buttons</h3>
                    <p>
                        <button class="btn btn-primary " type="button"><i class="fa fa-check"></i>&nbsp;Submit
                        </button>
                        <button class="btn btn-success " type="button"><i class="fa fa-upload"></i>&nbsp;&nbsp;<span
                                    class="bold">Upload</span></button>
                        <button class="btn btn-info " type="button"><i class="fa fa-paste"></i> Edit</button>
                        <button class="btn btn-warning " type="button"><i class="fa fa-warning"></i> <span
                                    class="bold">Warning</span></button>
                        <button class="btn btn-default " type="button"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;Map
                        </button>

                        <a href="" class="btn btn-success btn-facebook">
                            <i class="fa fa-facebook"> </i> Sign in with Facebook
                        </a>
                        <a class="btn btn-success btn-facebook btn-outline">
                            <i class="fa fa-facebook"> </i> Sign in with Facebook
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-user-md"></i>
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-group"></i>
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-exchange"></i>
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-check-circle-o"></i>
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-road"></i>
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-ambulance"></i>
                        </a>
                        <a class="btn btn-white btn-bitbucket">
                            <i class="fa fa-star"></i> Stared
                        </a>
                    </p>

                    <h3 class="font-bold">Toggle buttons Variations</h3>
                    <p>Button groups can act as a radio or a switch or even a single toggle. Below are some examples
                        click to see what happens</p>
                    <button data-toggle="button" class="btn btn-primary btn-outline" type="button">Single Toggle
                    </button>
                    <button data-toggle="button" class="btn btn-primary" type="button">Single Toggle</button>
                    <div data-toggle="buttons-checkbox" class="btn-group">
                        <button class="btn btn-primary active" type="button"><i class="fa fa-bold"></i> Bold
                        </button>
                        <button class="btn btn-primary" type="button"><i class="fa fa-underline"></i> Underline
                        </button>
                        <button class="btn btn-primary active" type="button"><i class="fa fa-italic"></i> Italic
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Circle Icon Buttons</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <p>
                                For buttons you can add <code>.btn-circle</code> to rounded buttons and make it
                                circle.
                            </p>

                            <h3 class="font-bold">Circle buttons</h3>
                            <br/>
                            <button class="btn btn-default btn-circle" type="button"><i class="fa fa-check"></i>
                            </button>
                            <button class="btn btn-primary btn-circle" type="button"><i class="fa fa-list"></i>
                            </button>
                            <button class="btn btn-success btn-circle" type="button"><i class="fa fa-link"></i>
                            </button>
                            <button class="btn btn-info btn-circle" type="button"><i class="fa fa-check"></i>
                            </button>
                            <button class="btn btn-warning btn-circle" type="button"><i class="fa fa-times"></i>
                            </button>
                            <button class="btn btn-danger btn-circle" type="button"><i class="fa fa-heart"></i>
                            </button>
                            <button class="btn btn-danger btn-circle btn-outline" type="button"><i
                                        class="fa fa-heart"></i>
                            </button>
                            <br/>
                            <br/>
                            <button class="btn btn-default btn-circle btn-lg" type="button"><i
                                        class="fa fa-check"></i>
                            </button>
                            <button class="btn btn-primary btn-circle btn-lg" type="button"><i
                                        class="fa fa-list"></i>
                            </button>
                            <button class="btn btn-success btn-circle btn-lg" type="button"><i
                                        class="fa fa-link"></i>
                            </button>
                            <button class="btn btn-info btn-circle btn-lg" type="button"><i class="fa fa-check"></i>
                            </button>
                            <button class="btn btn-warning btn-circle btn-lg" type="button"><i
                                        class="fa fa-times"></i>
                            </button>
                            <button class="btn btn-danger btn-circle btn-lg" type="button"><i
                                        class="fa fa-heart"></i>
                            </button>
                            <button class="btn btn-danger btn-circle btn-lg btn-outline" type="button"><i
                                        class="fa fa-heart"></i>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Rounded Buttons</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#" class="dropdown-item">Config option 1</a>
                                    </li>
                                    <li><a href="#" class="dropdown-item">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content float-e-margins">
                            <p>
                                You can also add <code>.btn-rounded</code> class to round buttons.
                            </p>

                            <h3 class="font-bold">Button Group</h3>
                            <p>
                                <a class="btn btn-default btn-rounded" href="#">Default</a>
                                <a class="btn btn-primary btn-rounded" href="#">Primary</a>
                                <a class="btn btn-success btn-rounded" href="#">Success</a>
                                <a class="btn btn-info btn-rounded" href="#">Info</a>
                                <a class="btn btn-warning btn-rounded" href="#">Warning</a>
                                <a class="btn btn-danger btn-rounded" href="#">Danger</a>
                                <a class="btn btn-danger btn-rounded btn-outline" href="#">Danger</a>
                                <br/>
                                <br/>
                                <a class="btn btn-primary btn-rounded btn-block" href="#"><i
                                            class="fa fa-info-circle"></i> Block rounded with icon button</a>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="{pigcms{$static_path}js/plugins/chartJs/Chart.min.js"></script>
    <script>
        $(document).ready(function() {

            var lineData = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 80, 81, 56, 55, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});

        });
    </script>
<!----------------------------------------    以下不要写代码     ------------------------------------------------>
<include file="Public:footer"/>