<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('DATA_EXPORT')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong><a href="{pigcms{:U('Area/index')}">{pigcms{:L('DATA_EXPORT')}</a></strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;">        </div>    </div>    <form action="" method="GET" id="export-form">        <input type="hidden" name="g" value="System">        <input type="hidden" name="c" value="Data">        <input type="hidden" name="a">        <input type="hidden" name="keyword">        <input type="hidden" name="searchtype">        <input type="hidden" name="begin_time">        <input type="hidden" name="end_time">        <input type="hidden" name="status">        <input type="hidden" name="pay_type">        <input type="hidden" name="city_id">    </form>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title">                        <h5>{pigcms{:L('DATA_EXPORT')}</h5>                        <div class="ibox-tools">                        </div>                    </div>                    <div class="ibox-content">                        <p>                            1. Choose a form                        </p>                        <p id="export_menu">                            <volist name="export_menu" id="vo">                                <button type="button" <if condition="$i eq 1">class="btn btn-primary"<else/>class="btn btn-default"</if> data-type="{pigcms{$key}" style="margin: 5px 2px">{pigcms{$vo}</button>                            </volist>                        </p>                        <p>                            2. Sort Information                        </p>                        <p>                        <div class="col-lg-12">                            <div class="row">                                <div class="form-group col-lg-3" id="filter_div">                                    <label>{pigcms{:L('F_FILTER')}</label>                                    <div class="input-group m-b">                                        <div class="input-group-prepend">                                            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button" aria-expanded="false" data-id="0">All </button>                                            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 35px; left: 0px; will-change: top, left;">                                                <li><a href="javascript:void(0);" data-id="sid">Store ID</a></li>                                                <li><a href="javascript:void(0);" data-id="id">User ID</a></li>                                            </ul>                                        </div>                                        <input type="text" class="form-control" name="filter">                                    </div>                                </div>                                <div class="form-group col-lg-3" id="data_5">                                    <label class="font-normal">Date Range</label>                                    <div class="input-daterange input-group" id="datepicker">                                        <input type="text" class="form-control-sm form-control" name="start">                                        <!--span class="input-group-addon">to value="{pigcms{:date('m/d/Y')}" </span-->                                        <input type="text" class="form-control-sm form-control" name="end">                                    </div>                                </div>                                <div class="form-group col-lg-2" id="status_div">                                    <label>Order Status</label>                                    <div class="input-group m-b">                                        <div class="input-group-prepend">                                            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button" aria-expanded="false" data-id="-1">All </button>                                            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 35px; left: 0px; will-change: top, left;">                                                <volist name="status_list" id="vo">                                                <li><a href="javascript:void(0);" data-id="{pigcms{$key}">{pigcms{$vo}</a></li>                                                </volist>                                            </ul>                                        </div>                                    </div>                                </div>                                <div class="form-group col-lg-2" id="payment_div">                                    <label>Payment Method</label>                                    <div class="input-group m-b">                                        <div class="input-group-prepend">                                            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button" aria-expanded="false" data-id="0">All </button>                                            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 35px; left: 0px; will-change: top, left;">                                                <volist name="pay_method" id="vo">                                                    <li><a href="javascript:void(0);" data-id="{pigcms{$key}">{pigcms{$vo.name}</a></li>                                                </volist>                                                <li><a href="javascript:void(0);" data-id="balance">{pigcms{:L('_BACK_BALANCE_')}</a></li>                                            </ul>                                        </div>                                    </div>                                </div>                                <div class="form-group col-lg-2" id="city_div">                                    <label>City</label>                                    <div class="input-group m-b">                                        <div class="input-group-prepend">                                            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button" aria-expanded="false" data-id="0">All </button>                                            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 35px; left: 0px; will-change: top, left;">                                                <volist name="city" id="vo">                                                    <li><a href="javascript:void(0);" data-id="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</a></li>                                                </volist>                                            </ul>                                        </div>                                    </div>                                </div>                            </div>                            <button type="button" class="btn btn-primary btn-lg" id="download_btn">Download</button>                        </div>                        </p>                    </div>                    <div class="ibox-content" style="margin-top: 10px;">                        <p id="export_menu">                            <button type="button" class="btn btn-primary" style="margin: 5px 2px">{pigcms{:L('STORE_EXPORT')}</button>                        </p>                        <p>                        <div class="col-lg-12">                            <div class="row">                                <div class="form-group col-lg-3" id="data_6">                                    <label class="font-normal">Date Range</label>                                    <div class="input-daterange input-group" id="datepicker">                                        <input type="text" class="form-control-sm form-control" name="start">                                        <!--span class="input-group-addon">to value="{pigcms{:date('m/d/Y')}" </span-->                                        <input type="text" class="form-control-sm form-control" name="end">                                    </div>                                </div>                                <div class="form-group col-lg-2" id="city_div_2">                                    <label>City</label>                                    <div class="input-group m-b">                                        <div class="input-group-prepend">                                            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button" aria-expanded="false" data-id="0">All </button>                                            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 35px; left: 0px; will-change: top, left;">                                                <volist name="city" id="vo">                                                    <li><a href="javascript:void(0);" data-id="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</a></li>                                                </volist>                                            </ul>                                        </div>                                    </div>                                </div>                                <div class="form-group col-lg-2">                                    <label>&nbsp;</label>                                    <div class="input-group m-b">                                        <div class="input-group-prepend">                                            <button type="button" class="btn btn-primary btn-lg" id="search_btn">Search</button>                                        </div>                                    </div>                                </div>                            </div>                        </div>                        </p>                        <p id="show_div">                        </p>                    </div>                </div>            </div>        </div>        <link href="{pigcms{$static_path}css/plugins/datapicker/datepicker3.css" rel="stylesheet">        <script src="{pigcms{$static_path}js/plugins/datapicker/bootstrap-datepicker.js"></script>        <script>            $('#export_menu').children('button').each(function () {                $(this).click(function () {                    $(this).addClass("btn-primary").siblings().removeClass('btn-primary');                    $(this).removeClass('btn-default').siblings().addClass("btn-default");                    changeFilter();                });            });            setMenuClick();            function setMenuClick() {                $('.dropdown-menu').find("li a").each(function () {                    $(this).click(function () {                        var name = $(this).html();                        var id = $(this).data('id');                        $(this).parents('.dropdown-menu').siblings('.dropdown-toggle').html(name);                        $(this).parents('.dropdown-menu').siblings('.dropdown-toggle').attr('data-id', id);                    });                });            }            function changeFilter() {                var type = $('#export_menu').children('button.btn-primary').data('type');                switch (type){                    case 'order':                        $('#filter_div').show();                        $('#data_5').show();                        $('#status_div').show();                        $('#payment_div').show();                        $('#city_div').show();                        $('#status_div').children('label').html("Order Status");                        var in_html = '';                        <volist name="status_list" id="vo">                        in_html += '<li><a href="javascript:void(0);" data-id="{pigcms{$key}">{pigcms{$vo}</a></li>';                        </volist>                        $('#status_div').find('button.dropdown-toggle').attr('data-id',-1);                        $('#status_div').find('button.dropdown-toggle').html("All");                        $('#status_div').find('.dropdown-menu').html(in_html);                        $('#filter_div').find('button.dropdown-toggle').attr('data-id',0);                        $('#filter_div').find('button.dropdown-toggle').html("All");                        var filter_html = '<li><a href="javascript:void(0);" data-id="sid">Store ID</a></li>';                        filter_html += '<li><a href="javascript:void(0);" data-id="id">User ID</a></li>';                        $('#filter_div').find('.dropdown-menu').html(filter_html);                        setMenuClick();                        break;                    case 'sales':                    case 'store':                    case 'store_ranking':                    case 'user_ranking':                        $('#filter_div').hide();                        $('#data_5').show();                        $('#status_div').hide();                        $('#payment_div').hide();                        $('#city_div').show();                        break;                    case 'order_store':                        $('#filter_div').show();                        $('#data_5').show();                        $('#status_div').hide();                        $('#payment_div').hide();                        $('#city_div').hide();                        $('#filter_div').find('button.dropdown-toggle').attr('data-id','sid');                        $('#filter_div').find('button.dropdown-toggle').html("Store ID");                        $('#filter_div').find('.dropdown-menu').html('');                        setMenuClick();                        break;                    case 'store_info':                        $('#filter_div').hide();                        $('#data_5').hide();                        $('#status_div').show();                        $('#payment_div').hide();                        $('#city_div').show();                        $('#status_div').children('label').html("Status");                        var in_html = '<li><a href="javascript:void(0);" data-id="-1">All</a></li>';                        in_html += '<li><a href="javascript:void(0);" data-id="1">Active</a></li>';                        in_html += '<li><a href="javascript:void(0);" data-id="0">Close</a></li>';                        $('#status_div').find('button.dropdown-toggle').attr('data-id',-1);                        $('#status_div').find('button.dropdown-toggle').html("All");                        $('#status_div').find('.dropdown-menu').html(in_html);                        setMenuClick();                        break;                    case 'courier_pay':                        $('#filter_div').hide();                        $('#data_5').show();                        $('#status_div').hide();                        $('#payment_div').hide();                        $('#city_div').hide();                        break;                    case 'courier_info':                        $('#filter_div').hide();                        $('#data_5').hide();                        $('#status_div').show();                        $('#payment_div').hide();                        $('#city_div').hide();                        $('#status_div').children('label').html("Status");                        var in_html = '<li><a href="javascript:void(0);" data-id="-1">All</a></li>';                        in_html += '<li><a href="javascript:void(0);" data-id="1">Active</a></li>';                        in_html += '<li><a href="javascript:void(0);" data-id="0">Inactive</a></li>';                        $('#status_div').find('button.dropdown-toggle').attr('data-id',1);                        $('#status_div').find('button.dropdown-toggle').html("Active");                        $('#status_div').find('.dropdown-menu').html(in_html);                        setMenuClick();                        break;                    case 'order_courier':                        $('#filter_div').show();                        $('#data_5').show();                        $('#status_div').hide();                        $('#payment_div').hide();                        $('#city_div').hide();                        $('#filter_div').find('button.dropdown-toggle').attr('data-id','uid');                        $('#filter_div').find('button.dropdown-toggle').html("Courier ID");                        $('#filter_div').find('.dropdown-menu').html('');                        setMenuClick();                        break;                    case 'user':                        $('#filter_div').hide();                        $('#data_5').show();                        $('#status_div').show();                        $('#status_div').children('label').html("Status");                        var in_html = '<li><a href="javascript:void(0);" data-id="-1">All</a></li>';                        in_html += '<li><a href="javascript:void(0);" data-id="1">Active</a></li>';                        in_html += '<li><a href="javascript:void(0);" data-id="0">Inactive</a></li>';                        $('#status_div').find('button.dropdown-toggle').attr('data-id',1);                        $('#status_div').find('button.dropdown-toggle').html("Active");                        $('#status_div').find('.dropdown-menu').html(in_html);                        setMenuClick();                        $('#payment_div').hide();                        $('#city_div').hide();                        break;                }            }            $('#data_5 .input-daterange,#data_6 .input-daterange').datepicker({                keyboardNavigation: false,                forceParse: false,                autoclose: true            });            $('#download_btn').click(function () {                var type = $('#export_menu').children('button.btn-primary').data('type');                var url = "/admin.php?g=System&c=Data&a="+type;                var filter_val = $('#filter_div').find('input[name="filter"]').val();                var filter_id = $('#filter_div').find('button.dropdown-toggle').attr('data-id');                var begin_time = $('#data_5').find('input[name="start"]').val();                var end_time = $('#data_5').find('input[name="end"]').val();                var status_val = $('#status_div').find('button.dropdown-toggle').attr('data-id');                var payment_val = $('#payment_div').find('button.dropdown-toggle').attr('data-id');                var city_id = $('#city_div').find('button.dropdown-toggle').attr('data-id');                $("#export-form").children('input[name="g"]').val("System");                $("#export-form").children('input[name="c"]').val("Data");                $("#export-form").children('input[name="a"]').val(type);                $("#export-form").children('input[name="keyword"]').val(filter_val);                $("#export-form").children('input[name="searchtype"]').val(filter_id);                $("#export-form").children('input[name="begin_time"]').val(begin_time);                $("#export-form").children('input[name="end_time"]').val(end_time);                $("#export-form").children('input[name="status"]').val(status_val);                $("#export-form").children('input[name="pay_type"]').val(payment_val);                $("#export-form").children('input[name="city_id"]').val(city_id);                $("#export-form").attr('action',url);                $("#export-form").submit();            });            $('#search_btn').click(function () {                var begin_time = $('#data_6').find('input[name="start"]').val();                var end_time = $('#data_6').find('input[name="end"]').val();                var city_id = $('#city_div_2').find('button.dropdown-toggle').attr('data-id');                var data = {                    'begin_time':begin_time,                    'end_time':end_time,                    'city_id':city_id                };                $.get("{pigcms{:U('Data/getStoreList')}",data,function(reData){                    if(reData) {                        var html = "";                        for (var i = 0; i < reData.length; i++) {                            var val = reData[i];                            html += "<a href='/merchant.php?g=Merchant&c=Shop&a=export_pdf&store_id=" + val['store_id'] + "&begin_time=" + begin_time + "&end_time=" + end_time + "' target='_blank'>" + val['store_name'] + "</a>";                        }                        $('#show_div').html(html);                        $('#show_div').children('a').each(function () {                            $(this).click(function () {                                $(this).css('color', '#1ab394');                            });                        });                    }                },'json');            });        </script>        <style>            #show_div a{                margin: 10px 20px;                text-decoration: underline;            }        </style><include file="Public:footer"/>