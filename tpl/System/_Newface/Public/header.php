<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>TUTTI Backend Management System</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--------------------------------  Bootstrap  -------------------------------------->

        <!--CSS-->
        <link href="{pigcms{$static_path}css/bootstrap.min.css?t={pigcms{$_SERVER.REQUEST_TIME}" rel="stylesheet">
        <link href="{pigcms{$static_path}/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="{pigcms{$static_path}css/animate.css" rel="stylesheet">
        <link href="{pigcms{$static_path}css/style.css?t={pigcms{$_SERVER.REQUEST_TIME}" rel="stylesheet">
        <!--JS-->
        <script src="{pigcms{$static_path}js/jquery-3.1.1.min.js"></script>
        <script src="{pigcms{$static_path}js/jquery.timer.min.js"></script>
        <script src="{pigcms{$static_path}js/popper.min.js"></script>
        <script src="{pigcms{$static_path}js/bootstrap.js"></script>

        <!--------------------------------  Toastr style  ----------------------------------->

        <link href="{pigcms{$static_path}css/plugins/toastr/toastr.min.css" rel="stylesheet">
        <!--        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style.css" />-->
        <!-- Gritter -->
<!--        <link href="{pigcms{$static_path}js/plugins/gritter/jquery.gritter.css" rel="stylesheet">-->
        <link href="{pigcms{$static_path}css/plugins/footable/footable.core.css" rel="stylesheet">

        <!--------------------------------  Pre Release  -------------------------------------->

        <script type="text/javascript">
            var kind_editor=null,
                static_public="{pigcms{$static_public}",
                static_path="{pigcms{$static_path}",
                system_index="{pigcms{:U('Index/index')}",
                choose_province="{pigcms{:U('Area/ajax_province')}",
                choose_city="{pigcms{:U('Area/ajax_city')}",
                choose_area="{pigcms{:U('Area/ajax_area')}",
                choose_circle="{pigcms{:U('Area/ajax_circle')}",
                choose_market="{pigcms{:U('Area/ajax_market')}",
                choose_map="{pigcms{:U('Map/frame_map')}",
                get_firstword="{pigcms{:U('Words/get_firstword')}",
                frame_show=<if condition="$_GET['frame_show']">true<else/>false</if>;
            var meal_alias_name = "{pigcms{$config.meal_alias_name}",
                parentShowHelpParam = [],
                parentShowIndex = false,
                choose_provincess="{pigcms{:U('Adver/ajax_province')}",
                choose_cityss="{pigcms{:U('Adver/ajax_city')}";
            var selected_module = "{pigcms{:strval($_GET['module'])}",
                selected_action = "{pigcms{:strval($_GET['action'])}",
                selected_url = "{pigcms{:urldecode(strval(htmlspecialchars_decode($_GET['url'])))}";
        </script>

        <!--		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>-->
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.form.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.colorpicker.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/jquery.validate.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js?v=21"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/jquery.colorpicker.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/screenfull.min.js"></script>

        <script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/common.js?v=21"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/index.js?v=21"></script>

	</head>


