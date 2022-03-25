<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Event/modify_message')}" enctype="multipart/form-data">
                            <input type="hidden" name="old_type" value="{pigcms{$_GET['type']}">
                            <input type="hidden" name="old_days" value="{pigcms{$_GET['days']}">
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_NAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" size="20" validate="maxlength:200,required:true" value="{pigcms{$message.name|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_TYPE')}</label>
                                <div class="col-sm-9">
                                    <select name="type" id="select_type" class="form-control">
                                        <volist name="type" id="vo">
                                            <option value="{pigcms{$i-1}" <if condition="$message.type eq ($i-1)">selected</if>>{pigcms{$vo}</option>
                                        </volist>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Equals</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="days" size="20" validate="maxlength:200,required:true" value="{pigcms{$message.days|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Sorting #</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sort" size="20" validate="maxlength:200,required:true" value="{pigcms{$message.sort|default='0'}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Time</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" name="send_time" style="width:120px;" id="d4311" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH:mm',quickSel:['%H:00','%H:15','%H:30','%H:45']})" <if condition="$message['send_time'] neq ''">value="{pigcms{$message.send_time}"</if>/>
                                    <span id="clear_begin" class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">{pigcms{:L('G_CLEAR')}</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="title" size="20" validate="maxlength:200,required:true" value="{pigcms{$message.title|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_CONTENT')}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="content" validate="maxlength:500,required:true">{pigcms{$message.content|default=''}</textarea>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STATUS_')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable">
                                        <label class="cb-enable <if condition="$message['status'] eq 1 or $message['status'] eq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_NORMAL_')}</span>
                                        <input type="radio" name="status" value="1" <if condition="$message['status'] eq 1 or $message['status'] eq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                    <span class="cb-disable">
                                        <label class="cb-disable <if condition="$message['status'] eq 0 and $message['status'] neq ''">selected</if>">
                                        <span>{pigcms{:L('_BACK_FORBID_')}</span>
                                        <input type="radio" name="status" value="0" <if condition="$message['status'] eq 0 and $message['status'] neq ''">checked="checked"</if>/>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
                                <input type="reset" value="取消" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<style>
    #clear_begin,#clear_end{
        line-height: 30px;
        cursor: pointer;
        color: #0e62cd;
        padding-left: 10px;
    }
</style>

<script>

    $(function(){
        var sel_type=$("#select_type").val();
        if(sel_type == 3){
            $('#city_tr').show();
        }else{
            $('#city_tr').hide();
        }
        $('#clear_begin').click(function () {
            $('input[name=begin_time]').val('');
        });
        $('#clear_end').click(function () {
            $('input[name=end_time]').val('');
        });
        $('#select_type').change(function () {
            var type = $(this).val();
            if(type == 3){
                $('#city_tr').show();
            }else{
                $('#city_tr').hide();
            }
        });
    });
</script>
<include file="Public:footer_inc"/>