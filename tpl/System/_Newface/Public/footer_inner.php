        <div class="footer">
        <!--    <div class="float-right">-->
        <!--        10GB of <strong>250GB</strong> Free.-->
        <!--    </div>-->
        </div>
    </div>
</div>
<include file="Public:toast_inc"/>

<!-- Mainly scripts -->
<script src="{pigcms{$static_path}js/popper.min.js"></script>
<script src="{pigcms{$static_path}js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="{pigcms{$static_path}js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="{pigcms{$static_path}js/plugins/flot/jquery.flot.js"></script>
<script src="{pigcms{$static_path}js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="{pigcms{$static_path}js/plugins/flot/jquery.flot.spline.js"></script>
<script src="{pigcms{$static_path}js/plugins/flot/jquery.flot.resize.js"></script>
<script src="{pigcms{$static_path}js/plugins/flot/jquery.flot.pie.js"></script>

<!-- Peity -->
<script src="{pigcms{$static_path}js/plugins/peity/jquery.peity.min.js"></script>
<script src="{pigcms{$static_path}js/demo/peity-demo.js"></script>

<!-- Custom and plugin javascript -->
<script src="{pigcms{$static_path}js/inspinia.js"></script>
<script src="{pigcms{$static_path}js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI -->
<script src="{pigcms{$static_path}js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- GITTER JS-->
<script src="{pigcms{$static_path}js/plugins/gritter/jquery.gritter.min.js"></script>

<!-- Sparkline -->
<script src="{pigcms{$static_path}js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="{pigcms{$static_path}js/demo/sparkline-demo.js"></script>

<script src="{pigcms{$static_path}js/plugins/dataTables/datatables.min.js"></script>
<script src="{pigcms{$static_path}js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

<script language="JavaScript">
    function changeLange(lang) {
        setCookie('lang', lang, 30);
        window.location.reload();
    }
</script>
</body>
</html>