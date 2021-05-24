<!-- Toast notifications -->
<div style="position: absolute; top: 00px; right: 400px;">
    <div class="toast toast1 toast-bootstrap" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fa fa-newspaper-o"> </i>
            <strong class="mr-auto m-l-sm" id="toast_title">Notification</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body" id="toast_content">
            Hello, you can push notifications to your visitors with this toast feature.
        </div>
    </div>
</div>

<script type="text/javascript">
    let tutti_toast_1 = $('.toast1');
    let tutti_toast_1_title = $('#toast_title');
    let tutti_toast_1_content = $('#toast_content');
    var func_call_name=null;
    function tutti_notification(title,content,need_delay=5000,func=null) {
        tutti_toast_1.toast({
            delay: need_delay,
            animation: true
        });
        tutti_toast_1_title.html(title);
        tutti_toast_1_content.html(content);
        //console.log("11");
        tutti_toast_1.toast('show');
        if (func!=null){
            func_call_name=func;
            self.setInterval("tutti_notifocation_call()",need_delay);
        }
    }
    function tutti_notifocation_call() {
        func_call_name();
    }
</script>